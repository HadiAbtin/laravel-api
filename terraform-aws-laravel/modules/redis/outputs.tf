# Outputs for Redis Module

output "endpoint" {
  description = "Redis cluster endpoint"
  value       = aws_elasticache_replication_group.main.primary_endpoint_address
  sensitive   = true
}

output "port" {
  description = "Redis cluster port"
  value       = aws_elasticache_replication_group.main.port
}

output "replication_group_id" {
  description = "Redis replication group ID"
  value       = aws_elasticache_replication_group.main.replication_group_id
}

output "arn" {
  description = "Redis replication group ARN"
  value       = aws_elasticache_replication_group.main.arn
}

# Security Group ID is now provided by VPC module
# This follows best practices for separation of concerns

output "subnet_group_name" {
  description = "Redis subnet group name"
  value       = aws_elasticache_subnet_group.main.name
}
