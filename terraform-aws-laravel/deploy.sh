#!/bin/bash

# Multi-Environment Deployment Script for Laravel API
# Usage: ./deploy.sh <environment> <action>
# Example: ./deploy.sh dev apply
# Example: ./deploy.sh staging destroy

set -e

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Function to print colored output
print_status() {
    echo -e "${BLUE}[INFO]${NC} $1"
}

print_success() {
    echo -e "${GREEN}[SUCCESS]${NC} $1"
}

print_warning() {
    echo -e "${YELLOW}[WARNING]${NC} $1"
}

print_error() {
    echo -e "${RED}[ERROR]${NC} $1"
}

# Check if environment and action are provided
if [ $# -ne 2 ]; then
    print_error "Usage: $0 <environment> <action>"
    print_error "Environments: dev, staging, prod"
    print_error "Actions: apply, destroy, plan, output"
    exit 1
fi

ENVIRONMENT=$1
ACTION=$2

# Validate environment
if [[ ! "$ENVIRONMENT" =~ ^(dev|staging|prod)$ ]]; then
    print_error "Invalid environment: $ENVIRONMENT"
    print_error "Valid environments: dev, staging, prod"
    exit 1
fi

# Validate action
if [[ ! "$ACTION" =~ ^(apply|destroy|plan|output)$ ]]; then
    print_error "Invalid action: $ACTION"
    print_error "Valid actions: apply, destroy, plan, output"
    exit 1
fi

# Set environment-specific variables
case $ENVIRONMENT in
    dev)
        ENV_COLOR=$GREEN
        ;;
    staging)
        ENV_COLOR=$YELLOW
        ;;
    prod)
        ENV_COLOR=$RED
        ;;
esac

print_status "Deploying to ${ENV_COLOR}$ENVIRONMENT${NC} environment..."

# Change to environment directory
ENV_DIR="environments/$ENVIRONMENT"
if [ ! -d "$ENV_DIR" ]; then
    print_error "Environment directory not found: $ENV_DIR"
    exit 1
fi

cd "$ENV_DIR"

# Check if terraform is initialized
if [ ! -d ".terraform" ]; then
    print_status "Initializing Terraform..."
    terraform init
fi

# Execute terraform command
case $ACTION in
    apply)
        print_warning "This will CREATE/UPDATE resources in $ENVIRONMENT environment!"
        read -p "Are you sure? (y/N): " -n 1 -r
        echo
        if [[ $REPLY =~ ^[Yy]$ ]]; then
            print_status "Applying Terraform configuration..."
            terraform apply -auto-approve
            print_success "Deployment completed successfully!"
            
            # Show outputs
            print_status "Deployment outputs:"
            terraform output
        else
            print_warning "Deployment cancelled."
            exit 0
        fi
        ;;
    destroy)
        print_error "This will DESTROY ALL resources in $ENVIRONMENT environment!"
        print_error "This action is IRREVERSIBLE!"
        read -p "Are you absolutely sure? Type 'yes' to confirm: " -r
        if [[ $REPLY == "yes" ]]; then
            print_status "Destroying Terraform resources..."
            terraform destroy -auto-approve
            print_success "Resources destroyed successfully!"
        else
            print_warning "Destruction cancelled."
            exit 0
        fi
        ;;
    plan)
        print_status "Planning Terraform changes..."
        terraform plan
        ;;
    output)
        print_status "Showing Terraform outputs..."
        terraform output
        ;;
esac

print_success "Operation completed successfully!"