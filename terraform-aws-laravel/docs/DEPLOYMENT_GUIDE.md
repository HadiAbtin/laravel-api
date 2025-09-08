# Deployment Guide

## 🚀 **Quick Start**

### **Prerequisites:**
- AWS CLI configured
- Terraform installed
- Docker installed (for building images)

### **Deploy All Environments:**
```bash
# Development (with latest image)
./deploy.sh dev apply

# Staging (with latest image)
./deploy.sh staging apply

# Production (with latest image)
./deploy.sh prod apply

# Deploy with specific image tag
./deploy.sh dev apply v1.2.3
./deploy.sh staging apply v2.0.0
./deploy.sh prod apply v2.1.0
```

## 📋 **Deployment Script Usage**

### **Available Commands:**
```bash
./deploy.sh <environment> <action> [image_tag]
```

### **Environments:**
- `dev` - Development environment
- `staging` - Staging environment
- `prod` - Production environment

### **Actions:**
- `apply` - Deploy/update infrastructure
- `plan` - Preview changes without applying
- `destroy` - Remove all infrastructure
- `output` - Show deployment outputs

### **Image Tags:**
- `latest` - Default tag (latest image)
- `v1.2.3` - Specific version tag
- `dev-123` - Development build tag
- `staging-456` - Staging build tag

### **Examples:**
```bash
# Deploy to development with latest image
./deploy.sh dev apply

# Deploy to staging with specific version
./deploy.sh staging apply v1.2.3

# Preview production changes
./deploy.sh prod plan

# View development outputs
./deploy.sh dev output

# Deploy with custom tag
./deploy.sh dev apply dev-feature-123

# Destroy development environment
./deploy.sh dev destroy
```

## 🐳 **ECR Repository Management**

### **Important Note:**
The ECR repository is **managed externally** from Terraform for better separation of concerns. This allows:
- Independent image management
- Flexible deployment strategies
- No Terraform state conflicts
- Better CI/CD integration

### **ECR Repository Details:**
```bash
# Repository URL format
<AWS_ACCOUNT_ID>.dkr.ecr.<AWS_REGION>.amazonaws.com/laravel-api

# Example
897722705454.dkr.ecr.us-east-1.amazonaws.com/laravel-api
```

### **Image Management Commands:**
```bash
# List all images
aws ecr list-images --repository-name laravel-api --region us-east-1

# Describe specific image
aws ecr describe-images --repository-name laravel-api --image-ids imageTag=latest

# Delete old images (optional)
aws ecr batch-delete-image --repository-name laravel-api --image-ids imageDigest=<digest>
```

### **Image Tag Strategy:**
- **`latest`**: Default tag for all environments
- **`v1.2.3`**: Semantic versioning for releases
- **`dev-123`**: Development build tags
- **`staging-456`**: Staging build tags
- **`prod-789`**: Production build tags

## 🔧 **Manual Deployment**

### **Step-by-Step Process:**

#### **1. Navigate to Environment:**
```bash
cd environments/dev  # or staging/prod
```

#### **2. Initialize Terraform:**
```bash
terraform init
```

#### **3. Plan Changes:**
```bash
terraform plan
```

#### **4. Apply Changes:**
```bash
terraform apply
```

#### **5. View Outputs:**
```bash
terraform output
```

## 🐳 **Docker Image Deployment**

### **Build and Push Image:**
```bash
cd ../laravel-api
./build-and-push.sh
```

### **Update ECS Service:**
```bash
aws ecs update-service \
  --cluster laravel-api-dev-cluster \
  --service laravel-api-dev-service \
  --force-new-deployment
```

## 🔍 **Verification**

### **Check Service Status:**
```bash
# ECS Service
aws ecs describe-services --cluster laravel-api-dev-cluster --services laravel-api-dev-service

# ALB Health
aws elbv2 describe-target-health --target-group-arn <target-group-arn>

# CloudWatch Logs
aws logs describe-log-streams --log-group-name /ecs/laravel-api-dev
```

### **Test Application:**
```bash
# Get CloudFront URL
./deploy.sh dev output | grep application_url

# Test API
curl https://<cloudfront-url>/api/ping
```

## 🛠️ **Troubleshooting**

### **Common Issues:**

#### **ECS Tasks Not Starting:**
```bash
# Check task definition
aws ecs describe-task-definition --task-definition laravel-api-dev

# Check service events
aws ecs describe-services --cluster laravel-api-dev-cluster --services laravel-api-dev-service --query 'services[0].events'
```

#### **Database Connection Issues:**
```bash
# Check RDS status
aws rds describe-db-instances --db-instance-identifier laravel-api-dev-db

# Check security groups
aws ec2 describe-security-groups --group-ids <rds-security-group-id>
```

#### **ALB Health Check Failures:**
```bash
# Check target health
aws elbv2 describe-target-health --target-group-arn <target-group-arn>

# Check ALB listeners
aws elbv2 describe-listeners --load-balancer-arn <alb-arn>
```

## 📊 **Monitoring**

### **CloudWatch Metrics:**
- ECS Service metrics
- ALB metrics
- RDS metrics
- ElastiCache metrics

### **Log Groups:**
- `/ecs/laravel-api-dev`
- `/aws/applicationloadbalancer/laravel-api-dev`
- `/aws/rds/instance/laravel-api-dev-db`

## 🔐 **Security**

### **Secrets Management:**
```bash
# View secrets
aws secretsmanager list-secrets --query 'SecretList[?contains(Name, `laravel-api-dev`)]'

# Get secret value
aws secretsmanager get-secret-value --secret-id laravel-api-dev-db-password-v2
```

### **IAM Roles:**
- ECS Execution Role
- ECS Task Role
- Auto Scaling Role

## 💰 **Cost Optimization**

### **Resource Sizing:**
- Development: Minimal resources
- Staging: Same as production
- Production: Optimized for performance

### **Auto Scaling:**
- CPU threshold: 70%
- Memory threshold: 80%
- Min capacity: 1 task
- Max capacity: 3-10 tasks

## 🚨 **Emergency Procedures**

### **Rollback Deployment:**
```bash
# Update ECS service with previous task definition
aws ecs update-service \
  --cluster laravel-api-dev-cluster \
  --service laravel-api-dev-service \
  --task-definition laravel-api-dev:5
```

### **Scale Down:**
```bash
# Set desired count to 0
aws ecs update-service \
  --cluster laravel-api-dev-cluster \
  --service laravel-api-dev-service \
  --desired-count 0
```

### **Emergency Destroy:**
```bash
# Destroy environment (use with caution)
./deploy.sh dev destroy
```

## 📞 **Support**

For deployment issues:
1. Check CloudWatch logs
2. Verify AWS resource status
3. Review Terraform state
4. Check security group rules
5. Validate environment variables

---

**This deployment guide provides comprehensive instructions for deploying and managing the Laravel API infrastructure across multiple environments.**
