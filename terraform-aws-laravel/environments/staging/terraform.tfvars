# Staging Environment Configuration

# AWS Configuration
aws_region = "us-east-1"

# Project Configuration
project_name = "laravel-api"
environment  = "staging"

# Docker Configuration
image_tag = "latest"

# Database Configuration
db_instance_class    = "db.t3.micro"
db_allocated_storage = 20
db_name              = "laravel"
db_username          = "laravel"

# Redis Configuration
redis_node_type = "cache.t3.micro"

# ECS Configuration
ecs_cpu    = 256
ecs_memory = 512

# Auto Scaling Configuration
ecs_auto_scaling_max_capacity = 3
ecs_auto_scaling_min_capacity = 1
ecs_cpu_target_value         = 70.0
ecs_memory_target_value      = 80.0

# Domain Configuration
domain_name    = ""
certificate_arn = ""

# CloudFront and Custom Domain Configuration
custom_domain = ""
ssl_certificate_arn = ""  # Optional for staging
route53_zone_id = ""       # Optional for staging
