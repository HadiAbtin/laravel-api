#!/bin/bash

# Build and Push Laravel API to ECR
# Usage: ./build-and-push.sh [image_tag]
# Example: ./build-and-push.sh latest
# Example: ./build-and-push.sh v1.2.3
# Example: ./build-and-push.sh dev-feature-123

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

# Configuration
AWS_REGION="us-east-1"
AWS_ACCOUNT_ID="897722705454"
PROJECT_NAME="laravel-api"
ECR_REPOSITORY="${PROJECT_NAME}"

# Get image tag from parameter or use latest
IMAGE_TAG=${1:-latest}

print_status "Building Laravel API Docker Image..."
print_status "Image Tag: ${IMAGE_TAG}"
print_status "ECR Repository: ${AWS_ACCOUNT_ID}.dkr.ecr.${AWS_REGION}.amazonaws.com/${ECR_REPOSITORY}"

# Build Docker image
print_status "Building Docker image..."
docker build --platform linux/x86_64 -t ${ECR_REPOSITORY}:${IMAGE_TAG} .

print_success "Docker image built successfully!"

# Tag for ECR
print_status "Tagging image for ECR..."
docker tag ${ECR_REPOSITORY}:${IMAGE_TAG} ${AWS_ACCOUNT_ID}.dkr.ecr.${AWS_REGION}.amazonaws.com/${ECR_REPOSITORY}:${IMAGE_TAG}

print_success "Image tagged for ECR"

# Login to ECR
print_status "Logging in to ECR..."
aws ecr get-login-password --region ${AWS_REGION} | docker login --username AWS --password-stdin ${AWS_ACCOUNT_ID}.dkr.ecr.${AWS_REGION}.amazonaws.com

print_success "Logged in to ECR"

# Push to ECR
print_status "Pushing image to ECR..."
docker push ${AWS_ACCOUNT_ID}.dkr.ecr.${AWS_REGION}.amazonaws.com/${ECR_REPOSITORY}:${IMAGE_TAG}

print_success "Image pushed to ECR successfully!"

echo ""
print_success "🎉 Build and push completed!"
echo ""
print_status "Repository URL: ${AWS_ACCOUNT_ID}.dkr.ecr.${AWS_REGION}.amazonaws.com/${ECR_REPOSITORY}:${IMAGE_TAG}"
echo ""
print_status "To deploy this image:"
print_status "  ./deploy.sh dev apply ${IMAGE_TAG}"
print_status "  ./deploy.sh staging apply ${IMAGE_TAG}"
print_status "  ./deploy.sh prod apply ${IMAGE_TAG}"
