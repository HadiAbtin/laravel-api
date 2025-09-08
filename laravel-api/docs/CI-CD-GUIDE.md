# Laravel API Multi-Environment CI/CD Guide

## Prerequisites

### Required Setup Before CI/CD

#### **🔐 Secrets Manager Setup (REQUIRED)**
Before running any CI/CD pipeline, ensure secrets are created in AWS Secrets Manager:

**Development Environment:**
```bash
aws secretsmanager create-secret \
  --name "laravel-api-dev-db-password-new" \
  --secret-string "DevDBPass123" \
  --region us-east-1

aws secretsmanager create-secret \
  --name "laravel-api-dev-app-key-new" \
  --secret-string "base64:$(openssl rand -base64 32)" \
  --region us-east-1
```

**Staging Environment:**
```bash
aws secretsmanager create-secret \
  --name "laravel-api-staging-db-password" \
  --secret-string "StagingDBPass123" \
  --region us-east-1

aws secretsmanager create-secret \
  --name "laravel-api-staging-app-key" \
  --secret-string "base64:$(openssl rand -base64 32)" \
  --region us-east-1
```

**Production Environment:**
```bash
aws secretsmanager create-secret \
  --name "laravel-api-prod-db-password" \
  --secret-string "ProdDBPass123" \
  --region us-east-1

aws secretsmanager create-secret \
  --name "laravel-api-prod-app-key" \
  --secret-string "base64:$(openssl rand -base64 32)" \
  --region us-east-1
```

#### **🐳 ECR Repository Setup (REQUIRED)**
Ensure ECR repository exists before CI/CD runs:

```bash
# Create ECR repository (if not exists)
aws ecr create-repository --repository-name laravel-api --region us-east-1
```

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
- **ECR Repository**: Single repository managed externally
- **Docker Images**: Tagged by environment and version
- **Terraform Modules**: Reusable across environments
- **Image Tag Management**: Per-environment deployment control

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
- **Development**: Auto-deploy on `develop` branch push (latest tag)
- **Staging**: Auto-deploy on `staging` branch push (latest tag)
- **Production**: Auto-deploy on `main` branch push (latest tag)
- **Manual Deployment**: Support for custom image tags

### 4. Health Check Stage
- **Environment Validation**: Post-deployment checks
- **Service Status**: ECS service health verification

## Image Tag Management

### **🔄 Flexible Image Tag Strategy**

**Automatic Deployments (CI/CD):**
- All automatic deployments use `latest` tag
- Images are tagged with `latest` during CI/CD build process
- Each environment gets the same `latest` image

**Manual Deployments:**
- Support for any custom image tag
- Deploy specific versions to any environment
- Mix and match image tags across environments

**Example Scenarios:**
```bash
# Deploy latest development build to staging
./deploy.sh staging apply latest

# Deploy specific version to production
./deploy.sh prod apply v1.2.3

# Deploy feature branch to development
./deploy.sh dev apply feature-auth-improvements

# Deploy hotfix to production
./deploy.sh prod apply hotfix-security-patch
```

**Image Tag Best Practices:**
- **Development**: Use `latest` for continuous integration
- **Staging**: Use version tags like `v1.2.3` for testing
- **Production**: Use stable version tags for releases
- **Feature Branches**: Use descriptive tags like `feature-auth-improvements`
- **Hotfixes**: Use `hotfix-` prefix for emergency fixes

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

## Manual Deployment Commands

### Infrastructure Deployment
```bash
# Deploy infrastructure for specific environment
cd terraform-aws-laravel
./deploy.sh dev apply
./deploy.sh staging apply
./deploy.sh prod apply

# Deploy with specific image tag
./deploy.sh dev apply v1.2.3
./deploy.sh staging apply latest
./deploy.sh prod apply v2.0.0
```

### Application Deployment
```bash
# Build and push Docker image with latest tag
cd laravel-api
./build-and-push.sh

# Build and push with specific tag
./build-and-push.sh v1.2.3
./build-and-push.sh dev-feature-123
./build-and-push.sh staging-456

# Update ECS service with new image
aws ecs update-service \
  --cluster laravel-api-dev-cluster \
  --service laravel-api-dev-service \
  --force-new-deployment
```

### Image Tag Management
```bash
# List available images
aws ecr list-images --repository-name laravel-api --region us-east-1

# Deploy specific image tag
terraform apply -var="image_tag=v1.2.3"
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

## 🌐 **Custom Domain Setup**

### **Using Cloudflare DNS (Recommended)**

After successful deployment, you can use your own domain with Cloudflare:

#### **Step 1: Get ALB URL**
```bash
# Get ALB URL from Terraform output
cd terraform-aws-laravel
./deploy.sh dev output | grep alb_url

# Example output:
# alb_url = "http://laravel-api-dev-alb-1234567890.us-east-1.elb.amazonaws.com"
```

#### **Step 2: Configure Cloudflare DNS**
1. **Login to Cloudflare Dashboard**
2. **Go to DNS Management**
3. **Create CNAME Records:**

**Development Environment:**
```
Type: CNAME
Name: laravel-api-dev
Target: laravel-api-dev-alb-1234567890.us-east-1.elb.amazonaws.com
Proxy: ✅ Enabled (Orange Cloud)
```

**Staging Environment:**
```
Type: CNAME
Name: laravel-api-staging
Target: laravel-api-staging-alb-1234567890.us-east-1.elb.amazonaws.com
Proxy: ✅ Enabled (Orange Cloud)
```

**Production Environment:**
```
Type: CNAME
Name: laravel-api
Target: laravel-api-prod-alb-1234567890.us-east-1.elb.amazonaws.com
Proxy: ✅ Enabled (Orange Cloud)
```

#### **Step 3: Access Your Services**
After DNS propagation (5-10 minutes), your services will be available at:

- **Development**: `https://laravel-api-dev.vboom.io`
- **Staging**: `https://laravel-api-staging.vboom.io`
- **Production**: `https://laravel-api.vboom.io`

#### **Step 4: Test Custom Domains**
```bash
# Test development environment
curl -I https://laravel-api-dev.vboom.io/api/ping

# Test staging environment
curl -I https://laravel-api-staging.vboom.io/api/ping

# Test production environment
curl -I https://laravel-api.vboom.io/api/ping
```

### **🔒 Cloudflare Benefits**
- ✅ **Free SSL/TLS**: Automatic HTTPS encryption
- ✅ **DDoS Protection**: Built-in security
- ✅ **CDN**: Global content delivery
- ✅ **Caching**: Improved performance
- ✅ **Analytics**: Traffic insights
- ✅ **Easy Setup**: Simple CNAME configuration

### **📋 Domain Configuration Examples**

**Complete Domain Setup:**
```bash
# Development
https://laravel-api-dev.vboom.io → Cloudflare → ALB → ECS

# Staging  
https://laravel-api-staging.vboom.io → Cloudflare → ALB → ECS

# Production
https://laravel-api.vboom.io → Cloudflare → ALB → ECS
```

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
