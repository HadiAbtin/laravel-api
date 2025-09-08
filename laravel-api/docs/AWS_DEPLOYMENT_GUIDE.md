# AWS Deployment Guide for Laravel API

## 🚀 **Overview**

This guide outlines the best approach for deploying a Laravel API project to AWS using modern DevOps practices including Infrastructure as Code (Terraform), containerization, and CI/CD pipelines.

## 🚀 **Prerequisites**

### **1. AWS Account Setup**
- AWS Account with appropriate permissions
- AWS CLI configured
- Terraform installed (>= 1.0)

### **2. Required AWS Services**
- **ECS Fargate**: Container orchestration
- **RDS**: Managed MySQL database
- **ElastiCache**: Managed Redis cache
- **ALB**: Application Load Balancer
- **ECR**: Container registry (managed externally)
- **VPC**: Virtual Private Cloud
- **CloudWatch**: Monitoring and logging
- **Secrets Manager**: Secure credential storage (managed externally)
- **Auto Scaling**: Automatic scaling based on metrics
- **VPC Endpoints**: Private access to AWS services
- **CloudFront**: Content Delivery Network
- **Route 53**: DNS management (optional)
- **ACM**: SSL certificate management

### **3. Development Environment**
- Docker installed
- Git configured
- PHP 8.4+ (for local development)
- Composer (for dependency management)

### **4. Pre-Deployment Requirements**

#### **🔐 Secrets Manager Setup (REQUIRED)**
Before deploying any environment, you **MUST** create the required secrets in AWS Secrets Manager:

**For Development Environment:**
```bash
# Database password
aws secretsmanager create-secret \
  --name "laravel-api-dev-db-password-new" \
  --description "Database password for Laravel API development environment" \
  --secret-string "DevDBPass123" \
  --region us-east-1

# Application key
aws secretsmanager create-secret \
  --name "laravel-api-dev-app-key-new" \
  --description "Laravel application key for development environment" \
  --secret-string "base64:$(openssl rand -base64 32)" \
  --region us-east-1
```

**For Staging Environment:**
```bash
# Database password
aws secretsmanager create-secret \
  --name "laravel-api-staging-db-password" \
  --description "Database password for Laravel API staging environment" \
  --secret-string "StagingDBPass123" \
  --region us-east-1

# Application key
aws secretsmanager create-secret \
  --name "laravel-api-staging-app-key" \
  --description "Laravel application key for staging environment" \
  --secret-string "base64:$(openssl rand -base64 32)" \
  --region us-east-1
```

**For Production Environment:**
```bash
# Database password
aws secretsmanager create-secret \
  --name "laravel-api-prod-db-password" \
  --description "Database password for Laravel API production environment" \
  --secret-string "ProdDBPass123" \
  --region us-east-1

# Application key
aws secretsmanager create-secret \
  --name "laravel-api-prod-app-key" \
  --description "Laravel application key for production environment" \
  --secret-string "base64:$(openssl rand -base64 32)" \
  --region us-east-1
```

#### **🐳 Docker Image Preparation (REQUIRED)**
Before deploying infrastructure, you **MUST** build and push your Docker image to ECR:

```bash
# Navigate to Laravel project directory
cd laravel-api

# Build and push with latest tag (default)
./build-and-push.sh

# Or build and push with specific tag
./build-and-push.sh v1.2.3
./build-and-push.sh dev-feature-123
./build-and-push.sh staging-456
```

**Important Notes:**
- Each environment can use different image tags
- You can deploy any image tag to any environment
- Default tag is `latest` if not specified
- Image must exist in ECR before deployment

## 🎯 **Recommended Architecture**

### **1. Infrastructure as Code (Terraform)**
```
📁 terraform-infrastructure/
├── main.tf
├── variables.tf
├── outputs.tf
├── modules/
│   ├── vpc/
│   ├── ecs/
│   ├── rds/
│   ├── alb/
│   └── redis/
└── environments/
    ├── dev/
    ├── staging/
    └── prod/
```

### **2. Container Strategy**
```
📁 laravel-api/
├── Dockerfile
├── docker-compose.yml
├── .dockerignore
└── nginx/
    └── default.conf
```

### **3. CI/CD Pipeline**
```
GitHub Actions → ECR (External) → ECS Fargate → ALB
```

**Note**: ECR repository is managed outside Terraform for better separation of concerns.

## 🏆 **Best Practice Approach**

### **✅ Recommended Solution:**
1. **Separate Terraform project** for Infrastructure management
2. **Dockerfile in Laravel project** for Containerization
3. **ECR repository** managed outside Terraform
4. **GitHub Actions** for CI/CD automation
5. **AWS Fargate + ALB** for scalable hosting
6. **Image tag management** per environment

## 📋 **Implementation Steps**

### **Step 1: Infrastructure Setup (Terraform)**
```hcl
# Required Infrastructure Components:
# - VPC + Subnets (Public/Private)
# - ECS Cluster (Fargate)
# - Application Load Balancer
# - RDS MySQL Database
# - ElastiCache Redis
# - ECR Repository
# - IAM Roles and Policies
# - Security Groups
# - Route Tables
```

### **Step 2: Application Containerization (Laravel)**
```dockerfile
# Multi-stage Dockerfile approach:
# - Base PHP image
# - Composer dependencies
# - Nginx + PHP-FPM
# - Laravel optimization
# - Security hardening
```

### **Step 3: CI/CD Pipeline (GitHub Actions)**
```yaml
# Pipeline stages:
# 1. Build → Test → Security Scan
# 2. Push to ECR → Deploy to ECS
# 3. Environment-specific deployments
# 4. Database migrations
# 5. Cache clearing
# 6. Health checks
```

### **Step 4: Monitoring & Logging**
```yaml
# Monitoring components:
# - CloudWatch Logs
# - Application Load Balancer logs
# - ECS service logs
# - Custom application metrics
# - Error tracking
# - Performance monitoring
```

## 🚀 **Deployment Process**

### **Step 1: Prepare Secrets (REQUIRED)**
```bash
# Create secrets for each environment before deployment
# See Prerequisites section for detailed commands
```

### **Step 2: Build and Push Docker Image (REQUIRED)**
```bash
# Navigate to Laravel project
cd laravel-api

# Build and push image with specific tag
./build-and-push.sh v1.2.3

# Or use default latest tag
./build-and-push.sh
```

### **Step 3: Deploy Infrastructure**
```bash
# Navigate to Terraform project
cd terraform-aws-laravel

# Deploy development environment with specific image tag
./deploy.sh dev apply v1.2.3

# Deploy staging environment
./deploy.sh staging apply latest

# Deploy production environment
./deploy.sh prod apply v1.2.3
```

### **Step 4: Verify Deployment**
```bash
# Check ECS service status
aws ecs describe-services --cluster laravel-api-dev-cluster --services laravel-api-dev-service

# Test API endpoints
curl https://your-cloudfront-domain/api/ping
curl https://your-cloudfront-domain/api/users
```

### **🔄 Image Tag Management**

**Flexible Deployment Strategy:**
- **Development**: Always use `latest` tag for continuous integration
- **Staging**: Use version tags like `v1.2.3` for testing
- **Production**: Use stable version tags like `v1.2.3` for releases

**Example Deployment Scenarios:**
```bash
# Deploy latest development build to staging
./deploy.sh staging apply latest

# Deploy specific version to production
./deploy.sh prod apply v1.2.3

# Deploy feature branch to development
./deploy.sh dev apply feature-auth-improvements
```

## 🎯 **Benefits of This Approach**

### **✅ Infrastructure as Code:**
- **Reproducible** environments across dev/staging/prod
- **Version controlled** infrastructure changes
- **Easy rollback** capabilities
- **Environment consistency** guarantee
- **Team collaboration** on infrastructure

### **✅ Containerization:**
- **Consistent** deployments across environments
- **Scalable** architecture with auto-scaling
- **Easy** local development with docker-compose
- **Portable** across different cloud providers
- **Isolated** application dependencies

### **✅ CI/CD Pipeline:**
- **Automated** deployments on code changes
- **Quality gates** (tests, security scans, linting)
- **Environment promotion** (dev → staging → prod)
- **Rollback** capabilities for failed deployments
- **Audit trail** of all deployments

### **✅ AWS Services Integration:**
- **Fargate**: Serverless container hosting
- **ALB**: Load balancing + SSL termination
- **RDS**: Managed database with backups
- **ElastiCache**: Managed Redis for caching
- **ECR**: Container registry (managed externally)
- **CloudWatch**: Comprehensive monitoring
- **Image Tag Management**: Per-environment deployment control

## 🌐 **Custom Domain Setup**

### **Option 1: Cloudflare DNS (Recommended)**

After successful deployment, you can use your own domain with Cloudflare:

#### **Step 1: Get ALB URL**
```bash
# Get ALB URL from Terraform output
cd terraform-aws-laravel
terraform output alb_url

# Example output:
# "http://laravel-api-dev-alb-1234567890.us-east-1.elb.amazonaws.com"
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

### **Option 2: CloudFront Custom Domain**

You can also use CloudFront with custom domains:

#### **Step 1: Create ACM Certificate**
```bash
# Create SSL certificate for your domain
aws acm request-certificate \
  --domain-name "*.vboom.io" \
  --validation-method DNS \
  --region us-east-1
```

#### **Step 2: Configure CloudFront**
Update Terraform configuration to use custom domain:

```hcl
# In modules/cloudfront/main.tf
resource "aws_cloudfront_distribution" "main" {
  # ... existing configuration ...
  
  aliases = ["laravel-api-dev.vboom.io"]
  
  viewer_certificate {
    acm_certificate_arn      = var.acm_certificate_arn
    ssl_support_method       = "sni-only"
    minimum_protocol_version = "TLSv1.2_2021"
  }
}
```

#### **Step 3: Update DNS**
Point your domain to CloudFront distribution:

```
Type: CNAME
Name: laravel-api-dev
Target: d1234567890.cloudfront.net
```

### **🔒 SSL/TLS Benefits**

**Cloudflare Proxy Benefits:**
- ✅ **Free SSL/TLS**: Automatic HTTPS encryption
- ✅ **DDoS Protection**: Built-in security
- ✅ **CDN**: Global content delivery
- ✅ **Caching**: Improved performance
- ✅ **Analytics**: Traffic insights

**CloudFront Benefits:**
- ✅ **AWS Integration**: Native AWS service
- ✅ **Custom SSL**: ACM certificate management
- ✅ **Edge Locations**: Global AWS infrastructure
- ✅ **Custom Headers**: Advanced configuration

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

**Testing Your Setup:**
```bash
# Test development environment
curl -I https://laravel-api-dev.vboom.io/api/ping

# Test staging environment
curl -I https://laravel-api-staging.vboom.io/api/ping

# Test production environment
curl -I https://laravel-api.vboom.io/api/ping
```

## 📁 **Project Structure**

### **Project 1: Infrastructure (Terraform)**
```
terraform-aws-laravel/
├── main.tf                 # Main infrastructure configuration
├── variables.tf            # Input variables
├── outputs.tf              # Output values
├── terraform.tfvars        # Variable values
├── modules/
│   ├── vpc/               # VPC and networking
│   ├── ecs/               # ECS cluster and services
│   ├── rds/               # RDS database
│   ├── alb/               # Application Load Balancer
│   └── redis/             # ElastiCache Redis
└── environments/
    ├── dev/               # Development environment
    ├── staging/           # Staging environment
    └── prod/              # Production environment
```

### **Project 2: Application (Laravel)**
```
laravel-api/
├── Dockerfile             # Multi-stage container build
├── docker-compose.yml     # Local development setup
├── .dockerignore          # Docker ignore patterns
├── .github/
│   └── workflows/
│       ├── ci.yml         # Continuous Integration
│       └── deploy.yml     # Continuous Deployment
├── nginx/
│   └── default.conf       # Nginx configuration
└── scripts/
    ├── entrypoint.sh      # Container startup script
    └── migrate.sh         # Database migration script
```

## 🔧 **Required Technologies**

### **Infrastructure:**
- **Terraform** (Infrastructure as Code)
- **AWS Provider** (Terraform provider)
- **GitHub Actions** (CI/CD automation)
- **AWS CLI** (Local development)

### **Application:**
- **Docker** (Containerization)
- **Nginx** (Web server)
- **PHP-FPM** (PHP processor)
- **Composer** (PHP dependencies)
- **Laravel** (PHP framework)

### **AWS Services:**
- **ECS Fargate** (Serverless container hosting)
- **ALB** (Application Load Balancer)
- **RDS** (Relational Database Service)
- **ElastiCache** (Redis caching)
- **ECR** (Elastic Container Registry)
- **CloudWatch** (Monitoring and logging)
- **IAM** (Identity and Access Management)
- **VPC** (Virtual Private Cloud)

## 🎯 **Next Implementation Steps**

### **Phase 1: Infrastructure Setup**
1. Create VPC and Subnets (Public/Private)
2. Set up ECS Cluster with Fargate
3. Configure Application Load Balancer
4. Create RDS MySQL instance
5. Set up ElastiCache Redis cluster
6. Configure IAM roles and policies
7. Set up security groups

### **Phase 2: Application Containerization**
1. Write optimized Dockerfile
2. Configure Nginx for Laravel
3. Optimize Laravel for production
4. Test container locally
5. Set up health checks

### **Phase 3: CI/CD Pipeline**
1. Set up GitHub Actions workflows
2. Create ECR repository (external management)
3. Implement automated deployment with image tags
4. Add environment-specific configurations
5. Test deployment pipeline

### **Phase 4: Image Tag Management**
1. Configure per-environment image tags
2. Implement deployment scripts with tag support
3. Set up CI/CD with dynamic tag assignment
4. Test multi-environment deployments
5. Document tag management process

### **Phase 5: Monitoring & Security**
1. Configure CloudWatch logging
2. Implement application monitoring
3. Set up security scanning
4. Configure backup strategies
5. Implement performance monitoring

## 🚀 **Expected Outcomes**

This approach provides:
- **Scalable** and **reliable** infrastructure
- **Automated** deployment processes
- **Cost-effective** AWS resource utilization
- **Easy** maintenance and updates
- **Professional** DevOps practices
- **Team collaboration** capabilities
- **Disaster recovery** options

## 📚 **Additional Resources**

- [AWS ECS Fargate Documentation](https://docs.aws.amazon.com/ecs/latest/developerguide/AWS_Fargate.html)
- [Terraform AWS Provider](https://registry.terraform.io/providers/hashicorp/aws/latest)
- [GitHub Actions Documentation](https://docs.github.com/en/actions)
- [Laravel Deployment Guide](https://laravel.com/docs/deployment)
- [Docker Best Practices](https://docs.docker.com/develop/dev-best-practices/)

---

**Note**: This guide provides a high-level overview. Detailed implementation will be covered in subsequent phases.
