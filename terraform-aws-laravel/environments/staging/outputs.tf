# Staging Environment Outputs

output "application_url" {
  description = "URL of the application"
  value       = "https://${module.cloudfront.cloudfront_domain_name}"
}

output "api_url" {
  description = "URL of the API"
  value       = "https://${module.cloudfront.cloudfront_domain_name}/api"
}

output "alb_url" {
  description = "URL of the Application Load Balancer"
  value       = "http://${module.alb.dns_name}"
}

output "cloudfront_domain_name" {
  description = "CloudFront distribution domain name"
  value       = module.cloudfront.cloudfront_domain_name
}

output "cloudfront_distribution_id" {
  description = "CloudFront distribution ID"
  value       = module.cloudfront.cloudfront_distribution_id
}

output "cloudfront_hosted_zone_id" {
  description = "CloudFront hosted zone ID"
  value       = module.cloudfront.cloudfront_hosted_zone_id
}

output "ecr_repository_url" {
  description = "ECR repository URL"
  value       = local.ecr_repository_url
}

output "ecr_repository_name" {
  description = "ECR repository name"
  value       = var.project_name
}

output "ecs_cluster_name" {
  description = "ECS cluster name"
  value       = module.ecs.cluster_name
}

output "ecs_service_name" {
  description = "ECS service name"
  value       = module.ecs.service_name
}

output "ecs_cluster_arn" {
  description = "ECS cluster ARN"
  value       = module.ecs.cluster_arn
}

output "ecs_task_definition_arn" {
  description = "ECS task definition ARN"
  value       = module.ecs.task_definition_arn
}

output "rds_endpoint" {
  description = "RDS endpoint"
  value       = module.rds.endpoint
  sensitive   = true
}

output "rds_port" {
  description = "RDS port"
  value       = module.rds.port
}

output "rds_database_name" {
  description = "RDS database name"
  value       = module.rds.database_name
}

output "redis_endpoint" {
  description = "Redis endpoint"
  value       = module.redis.endpoint
  sensitive   = true
}

output "redis_port" {
  description = "Redis port"
  value       = module.redis.port
}

output "vpc_id" {
  description = "VPC ID"
  value       = module.vpc.vpc_id
}

output "vpc_cidr_block" {
  description = "VPC CIDR block"
  value       = module.vpc.vpc_cidr_block
}

output "private_subnet_ids" {
  description = "Private subnet IDs"
  value       = module.vpc.private_subnet_ids
}

output "public_subnet_ids" {
  description = "Public subnet IDs"
  value       = module.vpc.public_subnet_ids
}

output "alb_arn" {
  description = "Application Load Balancer ARN"
  value       = module.alb.arn
}

output "alb_dns_name" {
  description = "Application Load Balancer DNS name"
  value       = module.alb.dns_name
}

output "alb_zone_id" {
  description = "Application Load Balancer zone ID"
  value       = module.alb.zone_id
}

output "secrets_manager_arns" {
  description = "Secrets Manager ARNs"
  value       = module.ecs.secrets_manager_arns
  sensitive   = true
}

output "generated_db_password" {
  description = "Generated database password"
  value       = module.ecs.generated_db_password
  sensitive   = true
}

output "generated_app_key" {
  description = "Generated application key"
  value       = module.ecs.generated_app_key
  sensitive   = true
}

output "aws_region" {
  description = "AWS region"
  value       = var.aws_region
}

output "environment" {
  description = "Environment name"
  value       = var.environment
}

output "project_name" {
  description = "Project name"
  value       = var.project_name
}

# Summary
output "deployment_summary" {
  description = "Deployment summary"
  value = {
    project_name    = var.project_name
    environment     = var.environment
    aws_region      = var.aws_region
    application_url = "https://${module.cloudfront.cloudfront_domain_name}"
    api_url         = "https://${module.cloudfront.cloudfront_domain_name}/api"
    alb_url         = "http://${module.alb.dns_name}"
    vpc_id          = module.vpc.vpc_id
    rds_endpoint    = module.rds.endpoint
    redis_endpoint  = module.redis.endpoint
    ecr_repository  = local.ecr_repository_url
  }
}