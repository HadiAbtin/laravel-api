# Laravel API Multi-Environment CI/CD Guide

## Overview
This project implements a comprehensive CI/CD pipeline for Laravel API with three environments: **Development**, **Staging**, and **Production**.

## Environment Strategy

### Branch Strategy
- **`main`** → Production Environment
- **`staging`** → Staging Environment  
- **`develop`** → Development Environment

### Deployment Flow
```
Feature Branch → Pull Request → develop → staging → main
     ↓              ↓              ↓         ↓        ↓
   Tests        Code Review    Deploy    Deploy   Deploy
   Build        Approval       Dev       Staging  Prod
```

## Infrastructure Architecture

### Shared Resources
- **ECR Repository**: Single repository for all environments
- **Docker Images**: Tagged by environment and version
- **Terraform Modules**: Reusable across environments

### Environment-Specific Resources
- **VPC**: Isolated per environment
- **RDS**: Separate databases per environment
- **ElastiCache**: Separate Redis instances per environment
- **ECS**: Separate clusters and services per environment
- **CloudFront**: Separate distributions per environment

## CI/CD Pipeline

### 1. Build and Test Stage
- **PHP Tests**: Unit and integration tests
- **Static Analysis**: Code quality checks
- **Dependency Check**: Security vulnerabilities

### 2. Docker Build Stage
- **Multi-platform Build**: AMD64 architecture
- **ECR Push**: Tagged images pushed to repository
- **Cache Optimization**: Build cache for faster builds

### 3. Deployment Stages
- **Development**: Auto-deploy on `develop` branch push
- **Staging**: Auto-deploy on `staging` branch push
- **Production**: Auto-deploy on `main` branch push

### 4. Health Check Stage
- **Environment Validation**: Post-deployment checks
- **Service Status**: ECS service health verification

## Manual Deployment

### Using Deployment Script
```bash
# Deploy to development
./deploy.sh dev apply

# Deploy to staging
./deploy.sh staging apply

# Deploy to production
./deploy.sh prod apply

# Destroy environment
./deploy.sh dev destroy
```

### Using Terraform Directly
```bash
# Development
cd terraform-aws-laravel/environments/dev
terraform init
terraform apply

# Staging
cd terraform-aws-laravel/environments/staging
terraform init
terraform apply

# Production
cd terraform-aws-laravel/environments/prod
terraform init
terraform apply
```

## Environment Configuration

### Development Environment
- **Purpose**: Feature development and testing
- **Resources**: Minimal (t3.micro instances)
- **Auto-scaling**: 1-3 tasks
- **Custom Domain**: None (uses CloudFront default)

### Staging Environment
- **Purpose**: Pre-production testing
- **Resources**: Same as production
- **Auto-scaling**: 1-3 tasks
- **Custom Domain**: Optional

### Production Environment
- **Purpose**: Live application
- **Resources**: Production-grade
- **Auto-scaling**: 1-10 tasks
- **Custom Domain**: Required for SSL

## GitHub Secrets Required

### AWS Credentials
```
AWS_ACCESS_KEY_ID
AWS_SECRET_ACCESS_KEY
```

### Environment Variables
- Set in GitHub repository settings
- Under "Secrets and variables" → "Actions"

## Monitoring and Logging

### CloudWatch Integration
- **ECS Logs**: Application logs
- **ALB Logs**: Access logs
- **RDS Logs**: Database logs
- **ElastiCache Logs**: Redis logs

### Health Checks
- **ECS Health**: Container health status
- **ALB Health**: Load balancer target health
- **Database Health**: RDS instance status

## Security Best Practices

### Infrastructure Security
- **VPC Isolation**: Private subnets for databases
- **Security Groups**: Restrictive firewall rules
- **Secrets Manager**: Secure credential storage
- **IAM Roles**: Least privilege access

### Application Security
- **HTTPS Only**: CloudFront SSL termination
- **Environment Variables**: Secure configuration
- **Database Encryption**: RDS encryption at rest
- **Redis Encryption**: ElastiCache encryption

## Cost Optimization

### Resource Sizing
- **Development**: Minimal resources
- **Staging**: Same as production
- **Production**: Optimized for performance

### Auto-scaling
- **CPU-based**: Scale on CPU utilization
- **Memory-based**: Scale on memory usage
- **Custom Metrics**: Application-specific scaling

## Troubleshooting

### Common Issues
1. **ECS Task Failures**: Check CloudWatch logs
2. **Database Connection**: Verify security groups
3. **Redis Connection**: Check ElastiCache status
4. **ALB Health**: Verify target group health

### Debug Commands
```bash
# Check ECS service status
aws ecs describe-services --cluster laravel-api-dev-cluster --services laravel-api-dev-service

# Check CloudWatch logs
aws logs describe-log-streams --log-group-name /ecs/laravel-api-dev

# Check ALB target health
aws elbv2 describe-target-health --target-group-arn <target-group-arn>
```

## Rollback Strategy

### Application Rollback
```bash
# Update ECS service with previous image
aws ecs update-service --cluster <cluster> --service <service> --task-definition <previous-task-def>
```

### Infrastructure Rollback
```bash
# Terraform rollback
terraform apply -var="image_tag=previous-tag"
```

## Best Practices

### Code Quality
- **Pull Request Reviews**: Required for all changes
- **Automated Tests**: Must pass before deployment
- **Static Analysis**: Code quality gates

### Deployment Safety
- **Blue-Green Deployment**: Zero-downtime deployments
- **Health Checks**: Post-deployment validation
- **Rollback Plan**: Quick recovery strategy

### Monitoring
- **Real-time Alerts**: Critical issue notifications
- **Performance Metrics**: Application performance tracking
- **Cost Monitoring**: Resource usage optimization
