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

# Generated Secrets Outputs
output "db_password" {
  description = "Generated database password"
  value       = random_password.db_password.result
  sensitive   = true
}

output "app_key" {
  description = "Generated Laravel application key"
  value       = "base64:${base64encode(random_password.app_key.result)}"
  sensitive   = true
}

output "secrets_manager_arns" {
  description = "ARNs of the generated secrets"
  value = {
    db_password = aws_secretsmanager_secret.db_password.arn
    app_key     = aws_secretsmanager_secret.app_key.arn
  }
}
