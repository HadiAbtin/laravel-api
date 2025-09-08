# Laravel API with Multi-Environment AWS Infrastructure

A comprehensive Laravel REST API project with automated AWS infrastructure deployment using Terraform, Docker, and CI/CD pipeline.

## 🚀 **Project Overview**

This project provides a complete solution for deploying a Laravel API across multiple environments (Development, Staging, Production) on AWS using modern DevOps practices.

### **Key Features:**
- ✅ **Laravel 9.x** REST API with OAuth2 authentication
- ✅ **Multi-Environment** AWS infrastructure (dev/staging/prod)
- ✅ **Docker** containerization with optimized images
- ✅ **Terraform** Infrastructure as Code
- ✅ **CI/CD Pipeline** with GitHub Actions
- ✅ **Auto-scaling** ECS Fargate services
- ✅ **CloudFront** CDN for global performance
- ✅ **Secrets Management** with AWS Secrets Manager
- ✅ **Image Tag Management** per environment
- ✅ **Custom Domain Support** with Cloudflare DNS
- ✅ **SSL/TLS** automatic encryption
- ✅ **Monitoring** with CloudWatch
- ✅ **Security** best practices

## 📁 **Project Structure**

```
laravel-api/
├── laravel-api/                    # Laravel Application
│   ├── app/                        # Application code
│   ├── config/                     # Configuration files
│   ├── database/                   # Migrations and seeders
│   ├── docs/                       # Application documentation
│   ├── docker/                     # Docker configuration
│   ├── public/                     # Public assets
│   ├── resources/                  # Views and assets
│   ├── routes/                     # API routes
│   ├── tests/                      # Test suites
│   ├── Dockerfile                  # Docker image definition
│   ├── docker-compose.yml          # Local development
│   └── build-and-push.sh          # Docker build script
├── terraform-aws-laravel/          # Infrastructure as Code
│   ├── environments/               # Environment-specific configs
│   │   ├── dev/                    # Development environment
│   │   ├── staging/                # Staging environment
│   │   └── prod/                   # Production environment
│   ├── modules/                    # Reusable Terraform modules
│   │   ├── vpc/                    # VPC and networking
│   │   ├── ecs/                    # ECS cluster and services
│   │   ├── rds/                    # MySQL database
│   │   ├── redis/                  # Redis cache
│   │   ├── alb/                    # Application Load Balancer
│   │   └── cloudfront/             # CloudFront CDN
│   ├── docs/                       # Infrastructure documentation
│   ├── deploy.sh                   # Deployment script
│   └── README.md                   # Infrastructure guide
├── .github/
│   └── workflows/
│       └── ci-cd.yml               # CI/CD pipeline
└── README.md                       # This file
```

## 🏗️ **Architecture**

### **AWS Infrastructure:**
- **ECS Fargate**: Serverless container orchestration
- **RDS MySQL**: Managed database service
- **ElastiCache Redis**: Managed Redis cache
- **Application Load Balancer**: Traffic distribution
- **CloudFront**: Global CDN
- **VPC**: Isolated network environment
- **ECR**: Container registry (managed externally)
- **Secrets Manager**: Secure credential storage (managed externally)
- **CloudWatch**: Monitoring and logging

### **Application Stack:**
- **Laravel 9.x**: PHP framework
- **OAuth2**: API authentication (Laravel Passport)
- **RBAC**: Role-based access control
- **UUID**: Primary keys for all models
- **API Transformers**: Structured API responses
- **Redis**: Caching and session storage

## 🌐 **Live Deployment**

### **Environment URLs:**
- **Development**: [https://laravel-api-dev.vboom.io](https://laravel-api-dev.vboom.io)
- **Staging**: [https://laravel-api-staging.vboom.io](https://laravel-api-staging.vboom.io)
- **Production**: [https://laravel-api.vboom.io](https://laravel-api.vboom.io)

### **Environment Info Endpoint:**
Check which environment you're accessing:
- **Dev**: [https://laravel-api-dev.vboom.io/api/users/env-info](https://laravel-api-dev.vboom.io/api/users/env-info)
- **Staging**: [https://laravel-api-staging.vboom.io/api/users/env-info](https://laravel-api-staging.vboom.io/api/users/env-info)
- **Production**: [https://laravel-api.vboom.io/api/users/env-info](https://laravel-api.vboom.io/api/users/env-info)

**Response Example:**
```json
{
  "success": true,
  "message": "Environment info retrieved successfully",
  "data": {
    "environment": "dev",
    "app_name": "Laravel",
    "app_url": "https://d2kkrofizwzdxb.cloudfront.net",
    "database_connected": true,
    "redis_connected": false,
    "cache_driver": "redis",
    "queue_driver": "redis",
    "session_driver": "redis",
    "version": "1.0.0-dev-test",
    "timestamp": "2025-09-08T22:23:54.383914Z"
  }
}
```

## 🚀 **Quick Start**

### **Prerequisites:**
- AWS CLI configured
- Terraform installed
- Docker installed
- Git

### **🔐 REQUIRED: Create Secrets First**
Before deploying any environment, you **MUST** create secrets in AWS Secrets Manager:

```bash
# Development Environment
aws secretsmanager create-secret \
  --name "laravel-api-dev-db-password-new" \
  --secret-string "DevDBPass123" \
  --region us-east-1

aws secretsmanager create-secret \
  --name "laravel-api-dev-app-key-new" \
  --secret-string "base64:$(openssl rand -base64 32)" \
  --region us-east-1

# Staging Environment
aws secretsmanager create-secret \
  --name "laravel-api-staging-db-password" \
  --secret-string "StagingDBPass123" \
  --region us-east-1

aws secretsmanager create-secret \
  --name "laravel-api-staging-app-key" \
  --secret-string "base64:$(openssl rand -base64 32)" \
  --region us-east-1

# Production Environment
aws secretsmanager create-secret \
  --name "laravel-api-prod-db-password" \
  --secret-string "ProdDBPass123" \
  --region us-east-1

aws secretsmanager create-secret \
  --name "laravel-api-prod-app-key" \
  --secret-string "base64:$(openssl rand -base64 32)" \
  --region us-east-1
```

### **🐳 REQUIRED: Create ECR Repository**
Before building images, create ECR repository:

```bash
# Create ECR repository (if not exists)
aws ecr create-repository --repository-name laravel-api --region us-east-1
```

### **1. Clone Repository:**
```bash
git clone <your-repository-url>
cd laravel-api
```

### **2. Deploy Development Environment:**
```bash
cd terraform-aws-laravel
./deploy.sh dev apply

# Or with specific image tag
./deploy.sh dev apply v1.2.3
```

### **3. Build and Push Docker Image:**
```bash
cd ../laravel-api

# Build with latest tag (default)
./build-and-push.sh

# Build with specific tag
./build-and-push.sh v1.2.3
./build-and-push.sh dev-feature-123
```

### **4. Test API:**
```bash
# Test basic endpoints
curl https://laravel-api-dev.vboom.io/api/ping
curl https://laravel-api-dev.vboom.io/api/users/env-info

# Test with authentication (requires valid token)
curl -H "Authorization: Bearer YOUR_TOKEN" https://laravel-api-dev.vboom.io/api/users

# Test all environments
curl https://laravel-api-dev.vboom.io/api/users/env-info      # Development
curl https://laravel-api-staging.vboom.io/api/users/env-info  # Staging
curl https://laravel-api.vboom.io/api/users/env-info          # Production
```

### **5. Setup Custom Domain (Optional):**
```bash
# Get ALB URL for custom domain setup
./deploy.sh dev output | grep alb_url

# Example: http://laravel-api-dev-alb-1234567890.us-east-1.elb.amazonaws.com
```

**Configure Cloudflare DNS:**
1. Login to Cloudflare Dashboard
2. Create CNAME record:
   ```
   Type: CNAME
   Name: laravel-api-dev
   Target: laravel-api-dev-alb-1234567890.us-east-1.elb.amazonaws.com
   Proxy: ✅ Enabled (Orange Cloud)
   ```
3. Access your service at: `https://laravel-api-dev.vboom.io`

## 📚 **Documentation**

### **Application Documentation:**
- **[Setup Guide](laravel-api/docs/SETUP_GUIDE.md)**: Local development setup
- **[Environment Variables](laravel-api/docs/ENVIRONMENT_VARIABLES.md)**: Configuration reference
- **[API Documentation](laravel-api/docs/api/)**: Complete API reference
- **[AWS Deployment Guide](laravel-api/docs/AWS_DEPLOYMENT_GUIDE.md)**: AWS-specific setup
- **[CI/CD Guide](laravel-api/docs/CI-CD-GUIDE.md)**: Pipeline documentation

### **Infrastructure Documentation:**
- **[Multi-Environment Guide](terraform-aws-laravel/docs/MULTI_ENVIRONMENT_GUIDE.md)**: Environment management
- **[Deployment Guide](terraform-aws-laravel/docs/DEPLOYMENT_GUIDE.md)**: Infrastructure deployment
- **[Terraform README](terraform-aws-laravel/README.md)**: Infrastructure overview

## 🔌 **API Reference**

### **Public Endpoints:**
```bash
# Health Check
GET /api/ping
# Response: "OK"

# Environment Info
GET /api/users/env-info
# Response: Environment details, DB/Redis status, config info

# User Statistics
GET /api/users/stats
# Response: User counts and statistics
```

### **Protected Endpoints (Require Authentication):**
```bash
# User Management
GET    /api/users              # List all users
POST   /api/users              # Create new user
GET    /api/users/{uuid}       # Get user by UUID
PUT    /api/users/{uuid}       # Update user
DELETE /api/users/{uuid}       # Delete user

# Authentication
POST   /api/register           # User registration
POST   /api/passwords/reset    # Password reset request
PUT    /api/passwords/reset    # Password reset confirmation
```

### **Authentication:**
All protected endpoints require Bearer token:
```bash
curl -H "Authorization: Bearer YOUR_OAUTH_TOKEN" \
     https://laravel-api-dev.vboom.io/api/users
```

## 🔧 **Environment Management**

### **Development Environment:**
- **Purpose**: Feature development and testing
- **Resources**: Minimal (t3.micro instances)
- **Auto-scaling**: 1-3 tasks
- **Image Tag**: `latest` (configurable)
- **Custom Domain**: `https://laravel-api-dev.vboom.io`
- **Cost**: ~$25/week
- **Deployment**: `./deploy.sh dev apply [tag]`

### **Staging Environment:**
- **Purpose**: Pre-production testing
- **Resources**: Same as production
- **Auto-scaling**: 1-3 tasks
- **Image Tag**: `latest` (configurable)
- **Custom Domain**: `https://laravel-api-staging.vboom.io`
- **Cost**: ~$25/week
- **Deployment**: `./deploy.sh staging apply [tag]`

### **Production Environment:**
- **Purpose**: Live application
- **Resources**: Production-grade
- **Auto-scaling**: 1-10 tasks
- **Image Tag**: `latest` (configurable)
- **Custom Domain**: `https://laravel-api.vboom.io`
- **Cost**: ~$25/week
- **Deployment**: `./deploy.sh prod apply [tag]`

## 🔄 **CI/CD Pipeline**

### **GitHub Actions Workflow:**
- **Build & Test**: PHP tests and static analysis
- **Docker Build**: Multi-platform image building
- **ECR Push**: Tagged images to external repository
- **ECS Update**: Automated service updates
- **Health Checks**: Post-deployment validation

### **Branch Strategy:**
```
Feature Branch → Pull Request → develop → Deploy to Dev (latest)
     ↓              ↓              ↓
   Tests        Code Review    Auto Deploy
   Build        Approval       ECS Update
```

### **Deployment Triggers:**
- **`develop`** branch → Development environment (latest tag)
- **`staging`** branch → Staging environment (latest tag)
- **`main`** branch → Production environment (latest tag)
- **Manual**: Support for custom image tags

## 🛠️ **Development Workflow**

### **1. Local Development:**
```bash
cd laravel-api
docker-compose up -d
composer install
php artisan migrate
php artisan test
```

### **2. Feature Development:**
```bash
git checkout -b feature/new-feature
# Make changes
git add .
git commit -m "Add new feature"
git push origin feature/new-feature
```

### **3. Create Pull Request:**
- Automated tests run
- Code review process
- Merge to `develop` branch

### **4. Deployment:**
```bash
# Build and push image with specific tag
./build-and-push.sh v1.2.3

# Deploy to development with specific tag
cd ../terraform-aws-laravel
./deploy.sh dev apply v1.2.3

# Deploy to staging
./deploy.sh staging apply v1.2.3

# Deploy to production
./deploy.sh prod apply v1.2.3
```

### **🔄 Image Tag Management:**
- **Flexible Deployment**: Deploy any image tag to any environment
- **Latest Tag**: Default for automatic deployments
- **Version Tags**: Use semantic versioning (v1.2.3)
- **Feature Tags**: Use descriptive names (feature-auth-improvements)
- **Hotfix Tags**: Use hotfix prefix (hotfix-security-patch)

### **🔐 Secrets Management:**
- **External Management**: Secrets created outside Terraform
- **Environment-Specific**: Each environment has its own secrets
- **Secure Storage**: AWS Secrets Manager encryption
- **Automatic Rotation**: Support for secret rotation

## 🔐 **Security Features**

### **Infrastructure Security:**
- VPC isolation for each environment
- Restrictive security groups
- Secrets Manager for credentials
- IAM roles with least privilege
- HTTPS-only communication
- Cloudflare DDoS protection
- SSL/TLS automatic encryption

### **Application Security:**
- OAuth2 authentication
- Role-based access control
- Input validation and sanitization
- SQL injection prevention
- XSS protection

## 📊 **Monitoring & Logging**

### **CloudWatch Integration:**
- ECS service metrics
- ALB access logs
- RDS performance metrics
- ElastiCache metrics
- Custom application logs

### **Health Checks:**
- ECS task health
- ALB target health
- Database connectivity
- Redis connectivity

## 💰 **Cost Management**

### **Resource Optimization:**
- Auto-scaling based on demand
- Spot instances for development
- Reserved instances for production
- CloudFront caching

### **Estimated Costs:**
- **Development**: ~$25/week
- **Staging**: ~$25/week
- **Production**: ~$25/week
- **Total**: ~$75/week (~$325/month)

## 🔍 **Status Check**

### **Quick Health Check:**
```bash
# Check all environments
curl -s https://laravel-api-dev.vboom.io/api/ping
curl -s https://laravel-api-staging.vboom.io/api/ping
curl -s https://laravel-api.vboom.io/api/ping

# Detailed environment info
curl -s https://laravel-api-dev.vboom.io/api/users/env-info | jq '.'
```

### **Expected Responses:**
- **Ping**: `"OK"`
- **Environment Info**: JSON with environment details
- **Users**: `401 Unauthorized` (expected without auth)

## 🚨 **Troubleshooting**

### **Common Issues:**
1. **ECS Tasks Not Starting**: Check CloudWatch logs
2. **Database Connection**: Verify security groups
3. **ALB Health Checks**: Check target group health
4. **Docker Build Failures**: Check Dockerfile syntax
5. **Redis Connection**: Check ElastiCache security groups

### **Debug Commands:**
```bash
# Check service status
./deploy.sh dev output

# View logs
aws logs describe-log-streams --log-group-name /ecs/laravel-api-dev

# Check ECS service
aws ecs describe-services --cluster laravel-api-dev-cluster

# Check environment info
curl https://laravel-api-dev.vboom.io/api/users/env-info
```

## 🤝 **Contributing**

### **Development Process:**
1. Fork the repository
2. Create a feature branch
3. Make changes with tests
4. Submit a pull request
5. Code review and merge

### **Code Standards:**
- Follow PSR-12 coding standards
- Write comprehensive tests
- Document all public methods
- Use meaningful commit messages

## 📞 **Support**

### **Documentation:**
- Check the `docs/` directories for detailed guides
- Review CloudWatch logs for errors
- Use `terraform plan` to preview changes

### **Issues:**
- Create GitHub issues for bugs
- Use discussions for questions
- Check existing issues first

## 📄 **License**

This project is licensed under the MIT License - see the [LICENSE](laravel-api/LICENSE) file for details.

## 🙏 **Acknowledgments**

- Laravel framework and community
- AWS services and documentation
- Terraform and HashiCorp
- Docker and containerization ecosystem

---

**This project demonstrates modern DevOps practices with Laravel, AWS, Terraform, Docker, and CI/CD automation. For detailed information, refer to the comprehensive documentation in the `docs/` directories.**

## 🔗 **Quick Links**

- **[Application Setup](laravel-api/docs/SETUP_GUIDE.md)**
- **[Infrastructure Deployment](terraform-aws-laravel/docs/DEPLOYMENT_GUIDE.md)**
- **[CI/CD Pipeline](laravel-api/docs/CI-CD-GUIDE.md)**
- **[Multi-Environment Guide](terraform-aws-laravel/docs/MULTI_ENVIRONMENT_GUIDE.md)**
- **[API Documentation](laravel-api/docs/api/)**
