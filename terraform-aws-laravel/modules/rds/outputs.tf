# Outputs for RDS Module

output "endpoint" {
  description = "RDS instance endpoint"
  value       = aws_db_instance.main.endpoint
  sensitive   = true
}

output "port" {
  description = "RDS instance port"
  value       = aws_db_instance.main.port
}

output "database_name" {
  description = "RDS database name"
  value       = aws_db_instance.main.db_name
}

output "username" {
  description = "RDS username"
  value       = aws_db_instance.main.username
}

output "password" {
  description = "RDS password from external secret"
  value       = data.aws_secretsmanager_secret_version.db_password.secret_string
  sensitive   = true
}

output "identifier" {
  description = "RDS instance identifier"
  value       = aws_db_instance.main.identifier
}

output "arn" {
  description = "RDS instance ARN"
  value       = aws_db_instance.main.arn
}

# Security Group ID is now provided by VPC module
# This follows best practices for separation of concerns
