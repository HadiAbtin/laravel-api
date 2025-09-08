# Development Environment Variables

# AWS Configuration
variable "aws_region" {
  description = "AWS region"
  type        = string
  default     = "us-east-1"
}

# Project Configuration
variable "project_name" {
  description = "Project name"
  type        = string
  default     = "laravel-api"
}

variable "environment" {
  description = "Environment name"
  type        = string
  default     = "prod"
}

# Docker Configuration
variable "image_tag" {
  description = "Docker image tag to deploy"
  type        = string
  default     = "latest"
}

# Domain Configuration
variable "domain_name" {
  description = "Domain name for SSL certificate"
  type        = string
  default     = ""
}

variable "certificate_arn" {
  description = "ARN of SSL certificate"
  type        = string
  default     = ""
}

# CloudFront and Custom Domain Configuration
variable "custom_domain" {
  description = "Custom domain for the API (e.g., laravel-api.vboom.io)"
  type        = string
  default     = null
}

variable "ssl_certificate_arn" {
  description = "ARN of the SSL certificate for CloudFront HTTPS"
  type        = string
  default     = null
}

variable "route53_zone_id" {
  description = "Route 53 hosted zone ID for the domain"
  type        = string
  default     = null
}

# Database Configuration
variable "db_instance_class" {
  description = "RDS instance class"
  type        = string
  default     = "db.t3.micro"
}

variable "db_allocated_storage" {
  description = "RDS allocated storage in GB"
  type        = number
  default     = 20
}

variable "db_name" {
  description = "Database name"
  type        = string
  default     = "laravel"
}

variable "db_username" {
  description = "Database username"
  type        = string
  default     = "laravel"
}

# Redis Configuration
variable "redis_node_type" {
  description = "Redis node type"
  type        = string
  default     = "cache.t3.micro"
}

# ECS Configuration
variable "ecs_cpu" {
  description = "ECS task CPU units"
  type        = number
  default     = 256
}

variable "ecs_memory" {
  description = "ECS task memory in MB"
  type        = number
  default     = 512
}

# ECS Auto Scaling Configuration
variable "ecs_auto_scaling_max_capacity" {
  description = "Maximum number of ECS tasks for auto scaling"
  type        = number
  default     = 3
}

variable "ecs_auto_scaling_min_capacity" {
  description = "Minimum number of ECS tasks for auto scaling"
  type        = number
  default     = 1
}

variable "ecs_cpu_target_value" {
  description = "Target CPU utilization percentage for auto scaling"
  type        = number
  default     = 70.0
}

variable "ecs_memory_target_value" {
  description = "Target memory utilization percentage for auto scaling"
  type        = number
  default     = 80.0
}
