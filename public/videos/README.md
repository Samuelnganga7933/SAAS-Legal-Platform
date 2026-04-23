# Background Videos Directory

## Purpose
This directory contains background videos for hero sections, message headers, and other dynamic visual elements in the client portal.

## Recommended Videos

### meteor-shower.mp4
- **Theme**: Cosmic/Meteor shower/Night sky
- **Duration**: 10-30 seconds (slow looping)
- **Resolution**: 1920x1080
- **Size**: <5MB (optimized)
- **Format**: MP4 (H.264 codec)
- **Usage**: Hero sections, inspiring moments
- **Suggested sources**:
  - pexels.com/videos: "meteor shower"
  - pixabay.com/videos: "night sky"

### aurora-borealis.mp4
- **Theme**: Aurora/Northern lights/Magical
- **Duration**: 10-30 seconds (slow looping)
- **Resolution**: 1920x1080
- **Size**: <5MB (optimized)
- **Format**: MP4 (H.264 codec)
- **Usage**: Hero sections, welcome screens
- **Suggested sources**:
  - pexels.com/videos: "aurora"
  - pixabay.com/videos: "lights"

### abstract-cosmic.mp4
- **Theme**: Abstract/Cosmic/Modern
- **Duration**: 10-30 seconds (slow looping)
- **Resolution**: 1920x1080
- **Size**: <5MB (optimized)
- **Format**: MP4 (H.264 codec)
- **Usage**: Section dividers, backgrounds
- **Suggested sources**:
  - pexels.com/videos: "abstract animation"
  - pixabay.com/videos: "cosmic"

## How to Find and Download Videos

### Pexels Videos (pexels.com/videos)
1. Go to pexels.com/videos
2. Search: "meteor shower", "aurora", "night sky", "space"
3. Click video thumbnail
4. Click "Download" button
5. Choose MP4 format
6. Save to this directory

### Pixabay Videos (pixabay.com/videos)
1. Go to pixabay.com/videos
2. Search: "meteor", "aurora", "cosmic", "night"
3. Click the video
4. Click "Download" - choose MP4
5. Save to this directory

### Vimeo/YouTube
- Search for free-license cosmic content
- Download using tools like youtube-dl (with proper licensing)

## Video Specifications

### Ideal Format
- **Container**: MP4
- **Video Codec**: H.264
- **Audio**: Muted (remove audio for background videos)
- **Duration**: 10-30 seconds (short loops)
- **Resolution**: 1920x1080 (Full HD)
- **Frame Rate**: 25-30 FPS
- **Bitrate**: 2-5 Mbps (quality vs. file size)
- **Size**: <5MB per video

### Preparing Videos

#### Remove Audio (Required for Background Videos)
```bash
# Using FFmpeg (free tool)
ffmpeg -i input.mp4 -c:v copy -an output.mp4
```

#### Optimize for Web
```bash
# Compress and convert
ffmpeg -i input.mp4 -c:v libx264 -preset medium -crf 23 -c:a aac -b:a 64k output.mp4

# Remove audio
ffmpeg -i input.mp4 -c:v libx264 -preset medium -crf 23 -an output.mp4
```

#### Convert to MP4 (if needed)
```bash
ffmpeg -i input.mov -c:v libx264 -c:a aac output.mp4
```

## HTML5 Video Implementation

### Basic Background Video
```html
<video autoplay muted loop style="
    width: 100%;
    height: 300px;
    object-fit: cover;
    border-radius: 10px;
">
    <source src="/videos/meteor-shower.mp4" type="video/mp4">
    Your browser does not support the video tag.
</video>
```

### Video with Overlay
```html
<div style="position: relative; height: 400px; border-radius: 10px; overflow: hidden;">
    <!-- Video -->
    <video autoplay muted loop style="
        width: 100%;
        height: 100%;
        object-fit: cover;
        position: absolute;
        top: 0;
        left: 0;
    ">
        <source src="/videos/meteor-shower.mp4" type="video/mp4">
    </video>

    <!-- Dark overlay -->
    <div style="
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(15, 23, 42, 0.5);
    "></div>

    <!-- Text content -->
    <div style="
        position: relative;
        z-index: 2;
        color: white;
        padding: 40px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        height: 100%;
    ">
        <h2>Your Content Here</h2>
        <p>Subtitle or description</p>
    </div>
</div>
```

## Blade Implementation Examples

### In resources/views/client/dashboard.blade.php
```blade
<!-- Hero Section with Video -->
<div style="
    position: relative;
    height: 400px;
    border-radius: 10px;
    overflow: hidden;
    margin-bottom: 32px;
">
    <video autoplay muted loop playsinline style="
        width: 100%;
        height: 100%;
        object-fit: cover;
        position: absolute;
        top: 0;
        left: 0;
    ">
        <source src="/videos/meteor-shower.mp4" type="video/mp4">
    </video>

    <!-- Overlay -->
    <div style="
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(180deg, 
            rgba(15, 23, 42, 0.6) 0%, 
            rgba(15, 23, 42, 0.3) 100%
        );
    "></div>

    <!-- Content -->
    <div style="
        position: relative;
        z-index: 1;
        color: white;
        padding: 60px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        height: 100%;
    ">
        <h2 style="font-size: 32px; font-weight: 700; margin-bottom: 16px;">
            Welcome to Your Legal Portal
        </h2>
        <p style="font-size: 16px; margin-bottom: 24px; max-width: 500px;">
            Professional legal services with a beautiful interface.
        </p>
    </div>
</div>
```

## Performance Considerations

### File Size
- Keep videos <5MB each
- 1MB per second = ~5MB for 5-second video
- Optimize codec and bitrate

### Mobile Performance
- Video might not autoplay on mobile
- Use `playsinline` attribute
- Provide fallback image
- Consider lighter background for mobile

### Browser Compatibility
```html
<video autoplay muted loop playsinline>
    <source src="/videos/meteor-shower.mp4" type="video/mp4">
    <source src="/videos/meteor-shower.webm" type="video/webm">
    <img src="/images/fallback.jpg" alt="Background" />
</video>
```

### Fallback for Old Browsers
```html
<div style="
    background-image: url('/images/fallback.jpg');
    background-size: cover;
    background-position: center;
">
    <video>...</video>
</div>
```

## Recommended Video Tools

### Free Video Editing
- **DaVinci Resolve** - Professional, free
- **OpenShot** - Simple, free
- **FFmpeg** - Command-line, powerful

### Video Compression
- **Handbrake** - Free video converter
- **Shotcut** - Free video editor
- **FFmpeg** - Ultimate command-line tool

### Online Converters
- **CloudConvert.com** - Convert formats online
- **Online-Convert.com** - Various video tools
- **Ezgif.com** - GIF to video and vice versa

## Usage Examples

### Full Page Background Video
```blade
<div style="position: relative; min-height: 100vh;">
    <video autoplay muted loop playsinline style="
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100vh;
        object-fit: cover;
        z-index: -1;
    ">
        <source src="/videos/meteor-shower.mp4" type="video/mp4">
    </video>
    
    <!-- Your content -->
</div>
```

### Responsive Video Section
```blade
<div style="
    position: relative;
    width: 100%;
    padding-bottom: 56.25%; /* 16:9 aspect ratio */
    height: 0;
    overflow: hidden;
    border-radius: 10px;
    margin-bottom: 32px;
">
    <video autoplay muted loop playsinline style="
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
    ">
        <source src="/videos/meteor-shower.mp4" type="video/mp4">
    </video>
</div>
```

## Optimization Checklist

- [ ] Video duration: 10-30 seconds
- [ ] Video size: <5MB
- [ ] Format: MP4 (H.264)
- [ ] Audio: Removed (background videos should be muted)
- [ ] Resolution: 1920x1080 or lower
- [ ] Tested on mobile devices
- [ ] Tested on slow connections
- [ ] Fallback image provided
- [ ] Overlay contrast tested
- [ ] Cross-browser compatibility verified

## Troubleshooting

### Video not playing?
1. Check file path and filename
2. Verify browser supports MP4 (most modern browsers do)
3. Try with different video format (WebM)
4. Check file permissions
5. Clear browser cache

### Video freezes or stutters?
1. Reduce resolution
2. Further compress the video
3. Lower frame rate to 24 FPS
4. Reduce bitrate

### Video too large?
1. Shorten duration
2. Reduce resolution to 1280x720
3. Use Handbrake with medium settings
4. Remove audio track
5. Use H.265 codec if supported

### Performance issues on mobile?
1. Use smaller resolution video for mobile
2. Use CSS media queries to switch sources
3. Use fallback image for mobile
4. Reduce video quality
5. Consider disabling autoplay on mobile

## Next Steps

1. Download 2-3 cosmic videos from Pexels or Pixabay
2. Optimize using FFmpeg or Handbrake
3. Save to this directory
4. Add to Blade templates
5. Test on different devices and networks
6. Monitor performance
7. Adjust overlay opacity for text readability

## Resources

- **Video Sites**: pexels.com/videos, pixabay.com/videos
- **FFmpeg**: ffmpeg.org
- **DaVinci Resolve**: blackmagicdesign.com
- **Video Guide**: developer.mozilla.org/en-US/docs/Web/HTML/Element/video
- **Performance**: web.dev/video-and-source-optimization

---

**Remember**: Always use videos that evoke the right mood (cosmic, professional, inspiring) and keep them short for quick loading!
