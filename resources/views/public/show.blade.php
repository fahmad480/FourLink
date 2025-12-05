<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $linkGroup->title }} - FourLink</title>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        body {
            background: {{ $linkGroup->background_color }};
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .public-container {
            max-width: 680px;
            width: 100%;
        }
        
        .profile-card {
            background: white;
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        
        .profile-thumbnail {
            width: 100%;
            max-height: 300px;
            object-fit: cover;
            border-radius: 15px;
            margin-bottom: 30px;
        }
        
        .component-link {
            display: block;
            background: white;
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 15px;
            text-decoration: none;
            color: #1f2937;
            transition: all 0.3s ease;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }
        
        .component-link:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            border-color: {{ $linkGroup->background_color }};
        }
        
        .component-content {
            background: white;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 15px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }
    </style>
</head>
<body>
    <div class="public-container">
        <div class="profile-card">
            @if($linkGroup->thumbnail)
                <img src="{{ asset('storage/' . $linkGroup->thumbnail) }}" 
                     alt="{{ $linkGroup->title }}" 
                     class="profile-thumbnail">
            @endif
            
            <h1 class="h2 fw-bold mb-2">{{ $linkGroup->title }}</h1>
            
            @if($linkGroup->description)
                <p class="text-muted mb-4">{{ $linkGroup->description }}</p>
            @endif
            
            <div class="components-list">
                @foreach($linkGroup->components as $component)
                    @if($component->type === 'link')
                        <a href="{{ $component->content }}" 
                           class="component-link" 
                           target="_blank">
                            <i class="fas fa-link me-2"></i>
                            {{ $component->title ?: $component->content }}
                        </a>
                    
                    @elseif($component->type === 'text')
                        <div class="component-content">
                            @if($component->title)
                                <h5 class="mb-2">{{ $component->title }}</h5>
                            @endif
                            <p class="mb-0">{{ $component->content }}</p>
                        </div>
                    
                    @elseif($component->type === 'image' && $component->file_path)
                        <div class="component-content">
                            @if($component->title)
                                <h5 class="mb-3">{{ $component->title }}</h5>
                            @endif
                            <img src="{{ asset('storage/' . $component->file_path) }}" 
                                 class="img-fluid rounded" 
                                 alt="{{ $component->title }}">
                        </div>
                    
                    @elseif($component->type === 'video' && $component->file_path)
                        <div class="component-content">
                            @if($component->title)
                                <h5 class="mb-3">{{ $component->title }}</h5>
                            @endif
                            <video controls class="w-100 rounded">
                                <source src="{{ asset('storage/' . $component->file_path) }}">
                                Your browser does not support the video tag.
                            </video>
                        </div>
                    
                    @elseif($component->type === 'file' && $component->file_path)
                        <a href="{{ asset('storage/' . $component->file_path) }}" 
                           class="component-link" 
                           download>
                            <i class="fas fa-download me-2"></i>
                            {{ $component->title ?: 'Download File' }}
                        </a>
                    
                    @elseif($component->type === 'embed')
                        <div class="component-content">
                            @if($component->title)
                                <h5 class="mb-3">{{ $component->title }}</h5>
                            @endif
                            {!! $component->content !!}
                        </div>
                    @endif
                @endforeach
            </div>
            
            <div class="mt-4 pt-4 border-top">
                <small class="text-muted">
                    <i class="fas fa-link"></i> Powered by <strong>FourLink</strong>
                </small>
            </div>
        </div>
    </div>
</body>
</html>
