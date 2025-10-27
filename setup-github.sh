#!/bin/bash

echo "🚀 Setting up GitHub repository for blaze-sms-service"
echo ""

# Check if already has remote
if git remote | grep -q "origin"; then
    echo "⚠️  Remote 'origin' already exists!"
    git remote -v
    echo ""
    read -p "Do you want to update it? (y/N): " -n 1 -r
    echo
    if [[ ! $REPLY =~ ^[Yy]$ ]]; then
        echo "Cancelled."
        exit 1
    fi
    git remote remove origin
fi

# Get GitHub username
read -p "Enter your GitHub username: " username
read -p "Enter repository name (default: blaze-sms-service): " repo_name
repo_name=${repo_name:-blaze-sms-service}

# Add remote
echo ""
echo "📦 Adding remote repository..."
git remote add origin "git@github.com:$username/$repo_name.git"

echo ""
echo "✅ Remote added successfully!"
echo ""
echo "Next steps:"
echo "1. Create a new repository on GitHub:"
echo "   Name: $repo_name"
echo "   URL: https://github.com/$username/$repo_name"
echo "   (DO NOT initialize with README, .gitignore, or license)"
echo ""
echo "2. Then run these commands:"
echo "   git push -u origin master"
echo ""
echo "3. Update composer.json in main project:"
echo "   Replace 'yourusername' with '$username'"
echo ""

