<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? $page_title . " - " . SITE_NAME : SITE_NAME; ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/lucide-static@0.462.0/font/lucide.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;500&display=swap');

        :root {
            --background: 224 50% 6%;
            --foreground: 214 32% 91%;
            --card: 224 47% 9%;
            --card-foreground: 214 32% 91%;
            --popover: 224 47% 9%;
            --popover-foreground: 214 32% 91%;
            --primary: 234 89% 66%;
            --primary-foreground: 0 0% 100%;
            --secondary: 224 47% 14%;
            --secondary-foreground: 214 32% 91%;
            --muted: 224 47% 14%;
            --muted-foreground: 215 16% 57%;
            --accent: 224 47% 18%;
            --accent-foreground: 214 32% 91%;
            --destructive: 0 62% 55%;
            --destructive-foreground: 0 0% 100%;
            --border: 224 30% 18%;
            --input: 224 30% 18%;
            --ring: 234 89% 66%;
            --radius: 0.75rem;
            --gradient-hero: linear-gradient(135deg, hsl(224 55% 4%) 0%, hsl(234 60% 12%) 50%, hsl(260 50% 15%) 100%);
            --gradient-primary: linear-gradient(135deg, hsl(234 89% 56%) 0%, hsl(260 84% 60%) 100%);
        }

        body {
            background-color: hsl(var(--background));
            color: hsl(var(--foreground));
            font-family: 'Inter', sans-serif;
        }

        .bg-gradient-hero { background-image: var(--gradient-hero); }
        .bg-gradient-primary { background-image: var(--gradient-primary); }

        .card {
            background-color: hsl(var(--card));
            border: 1px solid hsl(var(--border));
            border-radius: var(--radius);
        }

        .input {
            background-color: hsl(var(--background));
            border: 1px solid hsl(var(--border));
            border-radius: 0.5rem;
            padding: 0.5rem 0.75rem;
            width: 100%;
        }

        .btn-primary {
            background-color: hsl(var(--primary));
            color: hsl(var(--primary-foreground));
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: opacity 0.2s;
        }
        .btn-primary:hover { opacity: 0.9; }

        .text-muted { color: hsl(var(--muted-foreground)); }
    </style>
</head>
<body class="min-h-screen">
