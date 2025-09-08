locals {
  # اگر دامنه سفارشی و ARN معتبر داریم، از گواهی سفارشی استفاده کن
  use_custom_cert = (
    try(trim(var.custom_domain), "") != "" &&
    try(trim(var.ssl_certificate_arn), "") != ""
  )
}

# CloudFront Distribution for Laravel API
resource "aws_cloudfront_distribution" "main" {
  origin {
    domain_name = var.alb_dns_name
    origin_id   = "ALB-${var.project_name}-${var.environment}"

    custom_origin_config {
      http_port              = 80
      https_port             = 443
      origin_protocol_policy = "http-only"
      origin_ssl_protocols   = ["TLSv1.2"]
    }
  }

  enabled             = true
  is_ipv6_enabled     = true
  comment             = "CloudFront distribution for ${var.project_name}-${var.environment}"
  default_root_object = ""

  # فقط وقتی گواهی سفارشی داریم alias بفرست
  aliases = local.use_custom_cert ? [var.custom_domain] : []

  default_cache_behavior {
    allowed_methods  = ["DELETE", "GET", "HEAD", "OPTIONS", "PATCH", "POST", "PUT"]
    cached_methods   = ["GET", "HEAD"]
    target_origin_id = "ALB-${var.project_name}-${var.environment}"

    forwarded_values {
      query_string = true
      headers      = ["Authorization", "Content-Type", "Accept"]
      cookies {
        forward = "all"
      }
    }

    viewer_protocol_policy = "redirect-to-https"
    min_ttl                = 0
    default_ttl            = 0
    max_ttl                = 0
  }

  # Cache behavior for API routes
  ordered_cache_behavior {
    path_pattern     = "/api/*"
    allowed_methods  = ["DELETE", "GET", "HEAD", "OPTIONS", "PATCH", "POST", "PUT"]
    cached_methods   = ["GET", "HEAD"]
    target_origin_id = "ALB-${var.project_name}-${var.environment}"

    forwarded_values {
      query_string = true
      headers      = ["Authorization", "Content-Type", "Accept"]
      cookies {
        forward = "all"
      }
    }

    viewer_protocol_policy = "redirect-to-https"
    min_ttl                = 0
    default_ttl            = 0
    max_ttl                = 0
  }

  # Cache behavior for static assets
  ordered_cache_behavior {
    path_pattern     = "/assets/*"
    allowed_methods  = ["GET", "HEAD"]
    cached_methods   = ["GET", "HEAD"]
    target_origin_id = "ALB-${var.project_name}-${var.environment}"

    forwarded_values {
      query_string = false
      headers      = ["Origin"]
      cookies {
        forward = "none"
      }
    }

    viewer_protocol_policy = "redirect-to-https"
    min_ttl                = 86400
    default_ttl            = 86400
    max_ttl                = 31536000
  }

  # --- فقط یک بلاک viewer_certificate داریم، اما محتوایش شرطی و بدون null است
  dynamic "viewer_certificate" {
    for_each = [1]
    content {
      cloudfront_default_certificate = local.use_custom_cert ? false : true

      # وقتی سفارشی است، این‌ها ست می‌شوند؛ در غیر این صورت اصلاً ارسال نمی‌شوند (null نمی‌فرستیم)
      acm_certificate_arn      = local.use_custom_cert ? var.ssl_certificate_arn      : null
      ssl_support_method       = local.use_custom_cert ? "sni-only"                   : null
      minimum_protocol_version = local.use_custom_cert ? "TLSv1.2_2021"               : null
    }
  }

  restrictions {
    geo_restriction {
      restriction_type = "none"
    }
  }

  tags = {
    Name        = "${var.project_name}-${var.environment}-cloudfront"
    Project     = var.project_name
    Environment = var.environment
    Type        = "cloudfront"
  }
}

# Route 53 Record for custom domain
resource "aws_route53_record" "main" {
  count   = var.custom_domain != null && var.route53_zone_id != null && var.route53_zone_id != "" ? 1 : 0
  zone_id = var.route53_zone_id
  name    = var.custom_domain
  type    = "A"

  alias {
    name                   = aws_cloudfront_distribution.main.domain_name
    zone_id                = aws_cloudfront_distribution.main.hosted_zone_id
    evaluate_target_health = false
  }
}
