#!/bin/bash

# Build and Push Laravel API to ECR

set -e

# Configuration
AWS_REGION="us-east-1"
AWS_ACCOUNT_ID="897722705454"
PROJECT_NAME="laravel-api"
ECR_REPOSITORY="${PROJECT_NAME}"

# Get version from git tag or use latest
VERSION=${1:-latest}

echo "🚀 Building Laravel API Docker Image..."

# Build Docker image
docker build -t ${ECR_REPOSITORY}:${VERSION} .

echo "✅ Docker image built successfully!"

# Tag for ECR
docker tag ${ECR_REPOSITORY}:${VERSION} ${AWS_ACCOUNT_ID}.dkr.ecr.${AWS_REGION}.amazonaws.com/${ECR_REPOSITORY}:${VERSION}

echo "🏷️  Image tagged for ECR"

# Login to ECR
aws ecr get-login-password --region ${AWS_REGION} | docker login --username AWS --password-stdin ${AWS_ACCOUNT_ID}.dkr.ecr.${AWS_REGION}.amazonaws.com

echo "🔐 Logged in to ECR"

# Push to ECR
docker push ${AWS_ACCOUNT_ID}.dkr.ecr.${AWS_REGION}.amazonaws.com/${ECR_REPOSITORY}:${VERSION}

echo "📤 Image pushed to ECR successfully!"

echo "🎉 Build and push completed!"
echo "Repository: ${AWS_ACCOUNT_ID}.dkr.ecr.${AWS_REGION}.amazonaws.com/${ECR_REPOSITORY}:${VERSION}"
