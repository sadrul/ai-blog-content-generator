#!/bin/bash

# AI Blog Content Generator Build Script

echo "🚀 Building AI Blog Content Generator Plugin..."

# Check if node_modules exists
if [ ! -d "node_modules" ]; then
    echo "📦 Installing dependencies..."
    npm install
fi

# Build the plugin
echo "🔨 Building plugin..."
npm run build

# Check if build was successful
if [ $? -eq 0 ]; then
    echo "✅ Build completed successfully!"
    echo "📁 Built files are in the /build directory"
else
    echo "❌ Build failed!"
    exit 1
fi

echo "🎉 Plugin is ready for use!" 