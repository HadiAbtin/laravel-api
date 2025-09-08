# 📚 Documentation

This directory contains all project documentation for the Laravel API Starter Kit.

## 📖 Available Documents

### **Setup & Installation**
- **[SETUP_GUIDE.md](./SETUP_GUIDE.md)** - Complete setup guide for macOS and Debian/Ubuntu Linux
  - System requirements
  - Dependencies installation
  - Database configuration
  - Redis setup and configuration
  - Project setup steps
  - Troubleshooting tips

### **Configuration**
- **[ENVIRONMENT_VARIABLES.md](./ENVIRONMENT_VARIABLES.md)** - Complete guide to all environment variables
  - Application configuration
  - Database settings
  - Cache and session configuration
  - Mail and AWS settings
  - Security considerations
  - Configuration examples

### **Deployment & DevOps**
- **[AWS_DEPLOYMENT_GUIDE.md](./AWS_DEPLOYMENT_GUIDE.md)** - AWS deployment strategy using modern DevOps practices
  - Infrastructure as Code (Terraform)
  - Containerization with Docker
  - CI/CD with GitHub Actions
  - AWS Fargate + ALB architecture

### **API Documentation**
- **[api/](./api/)** - API Blueprint documentation
  - Complete API endpoints reference
  - Authentication flows
  - Data structures
  - Example requests/responses

## 🚀 Quick Start

1. **Local Development**: Follow [SETUP_GUIDE.md](./SETUP_GUIDE.md) for local setup
2. **Configuration**: Check [ENVIRONMENT_VARIABLES.md](./ENVIRONMENT_VARIABLES.md) for all configuration options
3. **Production Deployment**: Use [AWS_DEPLOYMENT_GUIDE.md](./AWS_DEPLOYMENT_GUIDE.md) for AWS deployment
4. **API Reference**: Check [api/](./api/) directory for API documentation

## 📝 Document Structure

```
docs/
├── README.md                    # This file
├── SETUP_GUIDE.md              # Local development setup
├── ENVIRONMENT_VARIABLES.md    # Environment variables guide
├── AWS_DEPLOYMENT_GUIDE.md     # AWS deployment guide
└── api/                        # API documentation
    ├── apiblueprint.apib       # Main API blueprint
    └── blueprint/              # Structured API docs
        ├── apidocs.apib
        ├── dataStructures/
        └── routes/
```

## 🔄 Document Updates

All documentation is maintained in this `docs/` directory. When adding new documentation:

1. Create new `.md` files in this directory
2. Update this `README.md` to include the new document
3. Follow the existing naming conventions
4. Include proper cross-references between documents

## 📋 Contributing

When contributing to documentation:

- Use clear, descriptive titles
- Include code examples where applicable
- Add troubleshooting sections for common issues
- Keep the documentation up-to-date with code changes
- Use consistent formatting and structure

---

**Last Updated**: September 5, 2025
