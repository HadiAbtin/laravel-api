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
- **Secrets Manager**: Secure credential storage
- **CloudWatch**: Monitoring and logging

### **Application Stack:**
- **Laravel 9.x**: PHP framework
- **OAuth2**: API authentication (Laravel Passport)
- **RBAC**: Role-based access control
- **UUID**: Primary keys for all models
- **API Transformers**: Structured API responses
- **Redis**: Caching and session storage

## 🚀 **Quick Start**

### **Prerequisites:**
- AWS CLI configured
- Terraform installed
- Docker installed
- Git

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
# Get CloudFront URL
cd ../terraform-aws-laravel
./deploy.sh dev output | grep application_url

# Test API endpoint
curl https://<cloudfront-url>/api/ping
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

## 🚨 **Troubleshooting**

### **Common Issues:**
1. **ECS Tasks Not Starting**: Check CloudWatch logs
2. **Database Connection**: Verify security groups
3. **ALB Health Checks**: Check target group health
4. **Docker Build Failures**: Check Dockerfile syntax

### **Debug Commands:**
```bash
# Check service status
./deploy.sh dev output

# View logs
aws logs describe-log-streams --log-group-name /ecs/laravel-api-dev

# Check ECS service
aws ecs describe-services --cluster laravel-api-dev-cluster
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
