<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $linkGroup->title }} - {{ $appName }}</title>
    
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    
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

            {{-- Social Media Links --}}
            @if($linkGroup->instagram_url || $linkGroup->facebook_url || $linkGroup->x_url || $linkGroup->threads_url || $linkGroup->website_url)
                <div class="social-links mb-4">
                    @if($linkGroup->instagram_url)
                        <a href="{{ $linkGroup->instagram_url }}" target="_blank" class="btn btn-link text-decoration-none" title="Instagram">
                            <i class="fab fa-instagram fa-2x text-danger"></i>
                        </a>
                    @endif
                    
                    @if($linkGroup->facebook_url)
                        <a href="{{ $linkGroup->facebook_url }}" target="_blank" class="btn btn-link text-decoration-none" title="Facebook">
                            <i class="fab fa-facebook fa-2x text-primary"></i>
                        </a>
                    @endif
                    
                    @if($linkGroup->x_url)
                        <a href="{{ $linkGroup->x_url }}" target="_blank" class="btn btn-link text-decoration-none" title="X (Twitter)">
                            <i class="fab fa-x-twitter fa-2x text-dark"></i>
                        </a>
                    @endif
                    
                    @if($linkGroup->threads_url)
                        <a href="{{ $linkGroup->threads_url }}" target="_blank" class="btn btn-link text-decoration-none" title="Threads">
                            <i class="fab fa-threads fa-2x text-dark"></i>
                        </a>
                    @endif
                    
                    @if($linkGroup->website_url)
                        <a href="{{ $linkGroup->website_url }}" target="_blank" class="btn btn-link text-decoration-none" title="Website">
                            <i class="fas fa-globe fa-2x text-info"></i>
                        </a>
                    @endif
                </div>
            @endif
            
            <div class="components-list">
                @foreach($linkGroup->components as $component)
                    @if($component->type === 'link')
                        <a href="{{ $component->content }}" 
                           class="component-link" 
                           target="_blank">
                            @if($component->emoji)
                                <span style="font-size: 1.5rem; margin-right: 8px;">{{ $component->emoji }}</span>
                            @else
                                <i class="fas fa-link me-2"></i>
                            @endif
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
                    
                    @elseif($component->type === 'video' && $component->content)
                        <div class="component-content">
                            @if($component->title)
                                <h5 class="mb-3">{{ $component->title }}</h5>
                            @endif
                            <div class="ratio ratio-16x9">
                                <iframe src="https://www.youtube.com/embed/{{ $component->content }}" 
                                        frameborder="0" 
                                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                                        allowfullscreen>
                                </iframe>
                            </div>
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
                    <i class="fas fa-link"></i> Powered by <strong>{{ $appName }}</strong>
                </small>
            </div>
        </div>
    </div>
</body>
</html>
