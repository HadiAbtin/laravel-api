# Terraform AWS Laravel Infrastructure

This project contains Terraform configurations for deploying Laravel API on AWS using modern infrastructure practices.

## 🏗️ Architecture

- **ECS Fargate**: Serverless container hosting
- **Application Load Balancer**: Load balancing and SSL termination
- **RDS MySQL**: Managed database
- **ElastiCache Redis**: Managed Redis for caching
- **VPC**: Isolated network environment
- **ECR**: Container registry

## 📁 Project Structure

```
terraform-aws-laravel/
├── main.tf                 # Main infrastructure configuration
├── variables.tf            # Input variables
├── outputs.tf              # Output values
├── terraform.tfvars.example # Example variable values
├── modules/
│   ├── vpc/               # VPC and networking module
│   ├── ecs/               # ECS cluster and services module
│   ├── rds/               # RDS database module
│   ├── alb/               # Application Load Balancer module
│   └── redis/             # ElastiCache Redis module
├── environments/
│   ├── dev/               # Development environment
│   ├── staging/           # Staging environment
│   └── prod/              # Production environment
└── README.md              # This file
```

## 🚀 Quick Start

### Prerequisites

1. **AWS CLI** installed and configured
2. **Terraform** installed (>= 1.0)
3. **AWS Account** with appropriate permissions

### Setup

1. **Clone and navigate to project:**
   ```bash
   cd terraform-aws-laravel
   ```

2. **Copy example variables:**
   ```bash
   cp terraform.tfvars.example terraform.tfvars
   ```

3. **Edit variables:**
   ```bash
   nano terraform.tfvars
   ```

4. **Initialize Terraform:**
   ```bash
   terraform init
   ```

5. **Plan deployment:**
   ```bash
   terraform plan
   ```

6. **Apply configuration:**
   ```bash
   terraform apply
   ```

## 🔧 Configuration

### Required Variables

- `aws_region`: AWS region (e.g., us-east-1)
- `project_name`: Project name (e.g., laravel-api)
- `environment`: Environment (dev, staging, prod)

### Optional Variables

- `domain_name`: Domain name for SSL certificate
- `db_instance_class`: RDS instance class
- `ecs_cpu`: ECS task CPU units
- `ecs_memory`: ECS task memory (MB)

## 🌍 Environments

### Development
- Minimal resources
- Single AZ deployment
- Basic monitoring

### Staging
- Production-like resources
- Multi-AZ deployment
- Enhanced monitoring

### Production
- High availability
- Multi-AZ deployment
- Full monitoring and alerting

## 📊 Outputs

After deployment, Terraform will output:
- Application Load Balancer URL
- RDS endpoint
- Redis endpoint
- ECR repository URL

## 🔒 Security

- VPC with private subnets
- Security groups with minimal access
- IAM roles with least privilege
- Encrypted RDS and Redis

## 📚 Documentation

- [AWS Provider Documentation](https://registry.terraform.io/providers/hashicorp/aws/latest)
- [ECS Fargate Documentation](https://docs.aws.amazon.com/ecs/latest/developerguide/AWS_Fargate.html)
- [RDS Documentation](https://docs.aws.amazon.com/rds/)

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Test thoroughly
5. Submit a pull request

## 📄 License

This project is licensed under the MIT License.

---

**Created**: September 5, 2025  
**Terraform Version**: >= 1.0  
**AWS Provider Version**: ~> 5.0
