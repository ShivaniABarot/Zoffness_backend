<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>{{ $announcement->title }}</title>

    <!-- Web‑safe body font -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">

    <style>
        /* ———  Core Layout  ——— */
        body{
            margin:0;
            padding:30px 20px;
            background:#f1f4f9;
            font-family:'Roboto',sans-serif;
        }
        .email-container{
            max-width:620px;
            margin:auto;
            background:#ffffff;
            padding:35px 40px;
            border-radius:10px;
            box-shadow:0 6px 20px rgba(0,0,0,.06);
            border-top:6px solid #4f46e5;   /* indigo‑600 */
            animation:fadeIn .9s ease-out;
        }

        /* ———  Headings & Text  ——— */
        h1{
            color:#4f46e5;
            font-size:26px;
            margin:0 0 14px;
            line-height:1.3;
            text-align:center;
        }
        p{
            color:#333;
            font-size:15px;
            line-height:1.6;
            margin:12px 0;
        }

        /* ———  Rich‑HTML block  ——— */
        .announcement-body{
            margin:25px 0;
            color:#1a1a1a;
            font-size:15px;
            line-height:1.6;
        }
        /* keep tables/paragraphs from $announcement->content tidy */
        .announcement-body table{
            width:100%;
            border-collapse:collapse;
        }
        .announcement-body th,
        .announcement-body td{
            padding:6px 8px;
            border:1px solid #e3e9f1;
        }

        /* ———  Date footer  ——— */
        .published{
            margin-top:30px;
            text-align:center;
            font-size:13px;
            color:#777;
        }

        /* ———  Simple fade‑in ——— */
        @keyframes fadeIn{
            from{opacity:0;transform:translateY(20px);}
            to{opacity:1;transform:translateY(0);}
        }

        /* ———  Responsive tweak ——— */
        @media only screen and (max-width:600px){
            .email-container{padding:25px 20px;}
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Title -->
        <h1>{{ $announcement->title }}</h1>

        <!-- Body (rich HTML coming from DB) -->
        <div class="announcement-body">
            {!! $announcement->content !!}
        </div>

        <!-- Date -->
        <p class="published">
            Published on {{ $announcement->created_at->format('F j, Y') }}
        </p>
    </div>
</body>
</html>
