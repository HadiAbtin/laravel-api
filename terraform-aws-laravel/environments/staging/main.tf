# Staging Environment Configuration

terraform {
  required_version = ">= 1.0"
  required_providers {
    aws = {
      source  = "hashicorp/aws"
      version = "~> 5.0"
    }
  }
}

# Configure the AWS Provider
provider "aws" {
  region = var.aws_region

  default_tags {
    tags = {
      Project     = var.project_name
      Environment = var.environment
      ManagedBy   = "Terraform"
      CreatedBy   = "Cloud Team"
    }
  }
}

# Data sources
data "aws_availability_zones" "available" {
  state = "available"
}

data "aws_caller_identity" "current" {}

# VPC Module
module "vpc" {
  source = "../../modules/vpc"

  project_name = var.project_name
  environment  = var.environment
  aws_region   = var.aws_region

  availability_zones = data.aws_availability_zones.available.names
}

# RDS Module
module "rds" {
  source = "../../modules/rds"

  project_name = var.project_name
  environment  = var.environment

  vpc_id             = module.vpc.vpc_id
  private_subnet_ids = module.vpc.private_subnet_ids
  rds_security_group_id = module.vpc.rds_security_group_id

  db_instance_class    = var.db_instance_class
  db_allocated_storage = var.db_allocated_storage
  db_name              = var.db_name
  db_username          = var.db_username
  db_password_secret_name = var.db_password_secret_name
}

# Redis Module
module "redis" {
  source = "../../modules/redis"

  project_name = var.project_name
  environment  = var.environment

  vpc_id             = module.vpc.vpc_id
  private_subnet_ids = module.vpc.private_subnet_ids
  redis_security_group_id = module.vpc.redis_security_group_id

  redis_node_type = var.redis_node_type
}

# ECR Repository URL (managed outside Terraform)
locals {
  ecr_repository_url = "${data.aws_caller_identity.current.account_id}.dkr.ecr.${var.aws_region}.amazonaws.com/${var.project_name}"
}

# Application Load Balancer Module
module "alb" {
  source = "../../modules/alb"

  project_name = var.project_name
  environment  = var.environment

  vpc_id            = module.vpc.vpc_id
  public_subnet_ids = module.vpc.public_subnet_ids

  domain_name = var.domain_name
  certificate_arn = var.certificate_arn
}

# CloudFront Module
module "cloudfront" {
  source = "../../modules/cloudfront"

  project_name = var.project_name
  environment  = var.environment
  alb_dns_name = module.alb.dns_name
  custom_domain = var.custom_domain
  ssl_certificate_arn = var.ssl_certificate_arn
  route53_zone_id = var.route53_zone_id
}

# ECS Module
module "ecs" {
  source = "../../modules/ecs"

  project_name = var.project_name
  environment  = var.environment

  vpc_id             = module.vpc.vpc_id
  private_subnet_ids = module.vpc.private_subnet_ids
  alb_target_group_arn = module.alb.target_group_arn
  alb_security_group_id = module.alb.security_group_id
  alb_arn = module.alb.arn
  alb_dns_name = module.alb.dns_name
  cloudfront_domain_name = module.cloudfront.cloudfront_domain_name
  custom_domain = var.custom_domain
  rds_security_group_id = module.vpc.rds_security_group_id
  redis_security_group_id = module.vpc.redis_security_group_id
  ecs_security_group_id = module.vpc.ecs_security_group_id

  image_tag           = var.image_tag
  ecr_repository_url  = local.ecr_repository_url
  ecs_cpu           = var.ecs_cpu
  ecs_memory        = var.ecs_memory

  rds_endpoint = module.rds.endpoint
  redis_endpoint = module.redis.endpoint

  # Secrets Configuration
  db_password_secret_name = var.db_password_secret_name
  app_key_secret_name      = var.app_key_secret_name

  # Auto Scaling Configuration
  ecs_auto_scaling_max_capacity = var.ecs_auto_scaling_max_capacity
  ecs_auto_scaling_min_capacity = var.ecs_auto_scaling_min_capacity
  ecs_cpu_target_value         = var.ecs_cpu_target_value
  ecs_memory_target_value      = var.ecs_memory_target_value
}