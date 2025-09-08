# Outputs for ECS Module

output "cluster_name" {
  description = "Name of the ECS cluster"
  value       = aws_ecs_cluster.main.name
}

output "cluster_arn" {
  description = "ARN of the ECS cluster"
  value       = aws_ecs_cluster.main.arn
}

output "service_name" {
  description = "Name of the ECS service"
  value       = aws_ecs_service.main.name
}

output "service_arn" {
  description = "ARN of the ECS service"
  value       = aws_ecs_service.main.id
}

output "task_definition_arn" {
  description = "ARN of the ECS task definition"
  value       = aws_ecs_task_definition.main.arn
}

output "execution_role_arn" {
  description = "ARN of the ECS execution role"
  value       = aws_iam_role.ecs_execution_role.arn
}

output "task_role_arn" {
  description = "ARN of the ECS task role"
  value       = aws_iam_role.ecs_task_role.arn
}

# External Secrets Outputs
output "db_password" {
  description = "Database password from external secret"
  value       = data.aws_secretsmanager_secret_version.db_password.secret_string
  sensitive   = true
}

output "app_key" {
  description = "Laravel application key from external secret"
  value       = data.aws_secretsmanager_secret_version.app_key.secret_string
  sensitive   = true
}

output "secrets_manager_arns" {
  description = "ARNs of the external secrets"
  value = {
    db_password = data.aws_secretsmanager_secret.db_password.arn
    app_key     = data.aws_secretsmanager_secret.app_key.arn
  }
}
