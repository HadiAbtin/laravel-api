# Multi-Environment Terraform Configuration Guide

## 🎯 **Objective:**
Manage dev, staging, and prod environments with separate infrastructure configurations

## 📁 **Recommended Structure:**

```
terraform-aws-laravel/
├── environments/
│   ├── dev/
│   │   ├── main.tf
│   │   ├── variables.tf
│   │   ├── outputs.tf
│   │   └── terraform.tfvars
│   ├── staging/
│   │   ├── main.tf
│   │   ├── variables.tf
│   │   ├── outputs.tf
│   │   └── terraform.tfvars
│   └── prod/
│       ├── main.tf
│       ├── variables.tf
│       ├── outputs.tf
│       └── terraform.tfvars
├── modules/
│   ├── vpc/
│   ├── rds/
│   ├── redis/
│   ├── ecr/
│   ├── alb/
│   ├── ecs/
│   └── cloudfront/
├── docs/
│   ├── MULTI_ENVIRONMENT_GUIDE.md
│   ├── DEPLOYMENT_GUIDE.md
│   └── TROUBLESHOOTING.md
└── deploy.sh
```

## 🔧 **Environment Configuration:**

### **Development Environment:**
- **Purpose**: Feature development and testing
- **Resources**: Minimal (t3.micro instances)
- **Auto Scaling**: 1-3 tasks
- **Custom Domain**: None (uses CloudFront default URL)
- **Cost**: ~$25/week

### **Staging Environment:**
- **Purpose**: Pre-production testing
- **Resources**: Same as production (t3.micro instances)
- **Auto Scaling**: 1-3 tasks
- **Custom Domain**: Optional
- **Cost**: ~$25/week

### **Production Environment:**
- **Purpose**: Live application
- **Resources**: Production-grade (t3.micro instances)
- **Auto Scaling**: 1-10 tasks
- **Custom Domain**: Required for SSL
- **Cost**: ~$25/week

## 🚀 **Deployment Methods:**

### **Method 1: Using Deployment Script (Recommended)**

The project includes a comprehensive deployment script (`deploy.sh`) that provides:

- **Safety checks** and confirmations
- **Colored output** for better visibility
- **Error handling** and validation
- **Environment-specific** deployments

#### **Usage:**
```bash
# Deploy to Development
./deploy.sh dev apply

# Deploy to Staging
./deploy.sh staging apply

# Deploy to Production
./deploy.sh prod apply

# Plan changes without applying
./deploy.sh dev plan

# View outputs
./deploy.sh dev output

# Destroy environment (with safety checks)
./deploy.sh dev destroy
```

#### **Deployment Script Features:**
- **Environment Validation**: Ensures valid environment names
- **Action Validation**: Validates deployment actions
- **Safety Prompts**: Confirms destructive operations
- **Colored Output**: Green for success, red for errors, yellow for warnings
- **Error Handling**: Graceful error handling and exit codes

### **Method 2: Direct Terraform Commands**

```bash
# Deploy to Development
cd environments/dev
terraform init
terraform plan
terraform apply

# Deploy to Staging
cd environments/staging
terraform init
terraform plan
terraform apply

# Deploy to Production
cd environments/prod
terraform init
terraform plan
terraform apply
```

## 📊 **Infrastructure Architecture:**

### **Shared Resources:**
- **ECR Repository**: Single repository for all environments
- **Docker Images**: Tagged by environment and version
- **Terraform Modules**: Reusable across environments

### **Environment-Specific Resources:**
- **VPC**: Isolated per environment
- **RDS**: Separate databases per environment
- **ElastiCache**: Separate Redis instances per environment
- **ECS**: Separate clusters and services per environment
- **CloudFront**: Separate distributions per environment
- **ALB**: Separate load balancers per environment

## 🔐 **Security Configuration:**

### **Infrastructure Security:**
- **VPC Isolation**: Private subnets for databases
- **Security Groups**: Restrictive firewall rules
- **Secrets Manager**: Secure credential storage per environment
- **IAM Roles**: Least privilege access

### **Environment Isolation:**
- **Separate State Files**: Each environment has its own Terraform state
- **Separate Secrets**: Environment-specific secrets in Secrets Manager
- **Separate VPCs**: Network isolation between environments
- **Separate IAM Roles**: Environment-specific permissions

## 📈 **Monitoring and Logging:**

### **CloudWatch Integration:**
- **ECS Logs**: Application logs per environment
- **ALB Logs**: Access logs per environment
- **RDS Logs**: Database logs per environment
- **ElastiCache Logs**: Redis logs per environment

### **Environment-Specific Monitoring:**
- **Separate Log Groups**: `/ecs/laravel-api-{env}`
- **Separate Alarms**: Environment-specific alerting
- **Separate Dashboards**: Environment-specific metrics

## 💰 **Cost Management:**

### **Resource Sizing Strategy:**
- **Development**: Minimal resources for cost efficiency
- **Staging**: Same as production for accurate testing
- **Production**: Optimized for performance and reliability

### **Auto-scaling Configuration:**
- **CPU-based Scaling**: Scale on CPU utilization (70% threshold)
- **Memory-based Scaling**: Scale on memory usage (80% threshold)
- **Custom Metrics**: Application-specific scaling triggers

### **Estimated Costs (Weekly):**
- **Development**: ~$25
- **Staging**: ~$25
- **Production**: ~$25
- **Total (3 environments)**: ~$75

## 🔄 **CI/CD Integration:**

### **GitHub Actions Pipeline:**
- **Automated Testing**: PHP tests and static analysis
- **Docker Build**: Multi-platform image building
- **Environment Deployment**: Automated deployment based on branch
- **Health Checks**: Post-deployment validation

### **Branch Strategy:**
```
Feature Branch → Pull Request → develop → Deploy to Dev
     ↓              ↓              ↓
   Tests        Code Review    Auto Deploy
   Build        Approval       ECS Update
```

## 🛠️ **Troubleshooting:**

### **Common Issues:**

#### **1. ECS Task Failures:**
```bash
# Check ECS service status
aws ecs describe-services --cluster laravel-api-dev-cluster --services laravel-api-dev-service

# Check CloudWatch logs
aws logs describe-log-streams --log-group-name /ecs/laravel-api-dev
```

#### **2. Database Connection Issues:**
```bash
# Verify RDS instance status
aws rds describe-db-instances --db-instance-identifier laravel-api-dev-db

# Check security groups
aws ec2 describe-security-groups --group-ids sg-xxxxx
```

#### **3. ALB Health Check Failures:**
```bash
# Check target group health
aws elbv2 describe-target-health --target-group-arn <target-group-arn>

# Check ALB status
aws elbv2 describe-load-balancers --names laravel-api-dev-alb
```

### **Debug Commands:**
```bash
# Check all resources in environment
./deploy.sh dev output

# Plan changes
./deploy.sh dev plan

# Check Terraform state
cd environments/dev
terraform state list
terraform state show <resource-name>
```

## 📚 **Best Practices:**

### **Environment Management:**
- **Consistent Naming**: Use consistent naming conventions
- **Resource Tagging**: Tag all resources with environment
- **State Management**: Keep state files secure and backed up
- **Secret Management**: Use AWS Secrets Manager for sensitive data

### **Deployment Safety:**
- **Blue-Green Deployment**: Zero-downtime deployments
- **Health Checks**: Post-deployment validation
- **Rollback Plan**: Quick recovery strategy
- **Monitoring**: Real-time alerting and monitoring

### **Code Quality:**
- **Pull Request Reviews**: Required for all changes
- **Automated Tests**: Must pass before deployment
- **Static Analysis**: Code quality gates
- **Documentation**: Keep documentation updated

## 🚀 **Getting Started:**

### **1. Initial Setup:**
```bash
# Clone repository
git clone <repository-url>
cd laravel-api/terraform-aws-laravel

# Make deployment script executable
chmod +x deploy.sh

# Configure AWS credentials
aws configure
```

### **2. Deploy Development Environment:**
```bash
# Deploy to development
./deploy.sh dev apply

# Check deployment status
./deploy.sh dev output
```

### **3. Deploy Staging Environment:**
```bash
# Deploy to staging
./deploy.sh staging apply

# Verify staging deployment
./deploy.sh staging output
```

### **4. Deploy Production Environment:**
```bash
# Deploy to production
./deploy.sh prod apply

# Monitor production deployment
./deploy.sh prod output
```

## 📞 **Support:**

For issues and questions:
- **Documentation**: Check this guide and related docs
- **Logs**: Review CloudWatch logs for errors
- **Terraform**: Use `terraform plan` to preview changes
- **AWS Console**: Use AWS console for resource inspection

---

**This multi-environment setup provides a robust, scalable, and cost-effective infrastructure for Laravel API deployment across development, staging, and production environments.**
