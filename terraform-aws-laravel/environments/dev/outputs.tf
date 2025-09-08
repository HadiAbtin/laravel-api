# Development Environment Outputs

# VPC Outputs
output "vpc_id" {
  description = "ID of the VPC"
  value       = module.vpc.vpc_id
}

output "vpc_cidr_block" {
  description = "CIDR block of the VPC"
  value       = module.vpc.vpc_cidr_block
}

output "public_subnet_ids" {
  description = "IDs of the public subnets"
  value       = module.vpc.public_subnet_ids
}

output "private_subnet_ids" {
  description = "IDs of the private subnets"
  value       = module.vpc.private_subnet_ids
}

# Database Outputs
output "rds_endpoint" {
  description = "RDS instance endpoint"
  value       = module.rds.endpoint
  sensitive   = true
}

output "rds_port" {
  description = "RDS instance port"
  value       = module.rds.port
}

output "rds_database_name" {
  description = "RDS database name"
  value       = module.rds.database_name
}

# Redis Outputs
output "redis_endpoint" {
  description = "Redis cluster endpoint"
  value       = module.redis.endpoint
  sensitive   = true
}

output "redis_port" {
  description = "Redis cluster port"
  value       = module.redis.port
}

# ECR Outputs
output "ecr_repository_url" {
  description = "ECR repository URL"
  value       = module.ecr.repository_url
}

output "ecr_repository_name" {
  description = "ECR repository name"
  value       = module.ecr.repository_name
}

# CloudFront Outputs
output "cloudfront_distribution_id" {
  description = "CloudFront distribution ID"
  value       = module.cloudfront.cloudfront_distribution_id
}

output "cloudfront_domain_name" {
  description = "CloudFront distribution domain name"
  value       = module.cloudfront.cloudfront_domain_name
}

output "cloudfront_hosted_zone_id" {
  description = "CloudFront distribution hosted zone ID"
  value       = module.cloudfront.cloudfront_hosted_zone_id
}
# Application Load Balancer Outputs
output "alb_dns_name" {
  description = "DNS name of the load balancer"
  value       = module.alb.dns_name
}

output "alb_zone_id" {
  description = "Zone ID of the load balancer"
  value       = module.alb.zone_id
}

output "alb_arn" {
  description = "ARN of the load balancer"
  value       = module.alb.arn
}

# ECS Outputs
output "ecs_cluster_name" {
  description = "Name of the ECS cluster"
  value       = module.ecs.cluster_name
}

output "ecs_cluster_arn" {
  description = "ARN of the ECS cluster"
  value       = module.ecs.cluster_arn
}

output "ecs_service_name" {
  description = "Name of the ECS service"
  value       = module.ecs.service_name
}

output "ecs_task_definition_arn" {
  description = "ARN of the ECS task definition"
  value       = module.ecs.task_definition_arn
}

# Application URLs
output "application_url" {
  description = "URL of the application"
  value       = var.custom_domain != "" ? "https://${var.custom_domain}" : "http://${module.alb.dns_name}"
}

output "api_url" {
  description = "URL of the API"
  value       = var.custom_domain != "" ? "https://${var.custom_domain}/api" : "http://${module.alb.dns_name}/api"
}

output "alb_url" {
  description = "Direct ALB URL (for debugging)"
  value       = "http://${module.alb.dns_name}"
}

# Environment Information
output "environment" {
  description = "Environment name"
  value       = var.environment
}

output "project_name" {
  description = "Project name"
  value       = var.project_name
}

output "aws_region" {
  description = "AWS region"
  value       = var.aws_region
}

# Summary
output "deployment_summary" {
  description = "Deployment summary"
  value = {
    project_name    = var.project_name
    environment     = var.environment
    aws_region      = var.aws_region
    application_url = var.custom_domain != "" ? "https://${var.custom_domain}" : "http://${module.alb.dns_name}"
    api_url         = var.custom_domain != "" ? "https://${var.custom_domain}/api" : "http://${module.alb.dns_name}/api"
    alb_url         = "http://${module.alb.dns_name}"
    vpc_id          = module.vpc.vpc_id
    rds_endpoint    = module.rds.endpoint
    redis_endpoint  = module.redis.endpoint
    ecr_repository  = module.ecr.repository_url
  }
}

# Generated Secrets Outputs
output "generated_db_password" {
  description = "Generated database password"
  value       = module.ecs.db_password
  sensitive   = true
}

output "generated_app_key" {
  description = "Generated Laravel application key"
  value       = module.ecs.app_key
  sensitive   = true
}

output "secrets_manager_arns" {
  description = "ARNs of the generated secrets in Secrets Manager"
  value       = module.ecs.secrets_manager_arns
}
