<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Developer Portfolio</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            color: #333;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .hero {
            text-align: center;
            padding: 60px 20px;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            margin-bottom: 40px;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .hero h1 {
            font-size: 3.5em;
            margin-bottom: 20px;
            background: linear-gradient(45deg, #fff, #f0f0f0);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .hero p {
            font-size: 1.3em;
            color: rgba(255, 255, 255, 0.9);
            margin-bottom: 30px;
        }

        .cta-buttons {
            display: flex;
            gap: 20px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .btn {
            padding: 15px 30px;
            border: none;
            border-radius: 50px;
            font-size: 1.1em;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
            font-weight: 600;
        }

        .btn-primary {
            background: linear-gradient(45deg, #ff6b6b, #ee5a24);
            color: white;
            box-shadow: 0 8px 30px rgba(238, 90, 36, 0.3);
        }

        .btn-secondary {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            border: 2px solid rgba(255, 255, 255, 0.3);
        }

        .btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.2);
        }

        .section {
            background: rgba(255, 255, 255, 0.95);
            padding: 40px;
            margin-bottom: 30px;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
        }

        .section h2 {
            font-size: 2.5em;
            margin-bottom: 30px;
            text-align: center;
            color: #2c3e50;
        }

        .skills-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-top: 30px;
        }

        .skill-card {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            padding: 30px;
            border-radius: 15px;
            color: white;
            text-align: center;
            transition: transform 0.3s ease;
        }

        .skill-card:hover {
            transform: translateY(-5px);
        }

        .skill-card h3 {
            font-size: 1.5em;
            margin-bottom: 15px;
        }

        .projects-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 25px;
            margin-top: 30px;
        }

        .project-card {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }

        .project-card:hover {
            transform: translateY(-8px);
        }

        .project-image {
            height: 200px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.2em;
        }

        .project-content {
            padding: 25px;
        }

        .project-content h3 {
            font-size: 1.4em;
            margin-bottom: 10px;
            color: #2c3e50;
        }

        .coffee-section {
            background: linear-gradient(135deg, #c0392b 0%, #8e44ad 100%);
            color: white;
            text-align: center;
        }

        .coffee-section h2 {
            color: white;
        }

        .coffee-card {
            background: rgba(255, 255, 255, 0.1);
            padding: 30px;
            border-radius: 15px;
            margin: 20px auto;
            max-width: 500px;
            backdrop-filter: blur(10px);
        }

        .price-tag {
            font-size: 2em;
            font-weight: bold;
            margin: 20px 0;
        }

        .contact {
            background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
            color: white;
            text-align: center;
        }

        .contact h2 {
            color: white;
        }

        .social-links {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 20px;
            flex-wrap: wrap;
        }

        .social-link {
            padding: 12px 25px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 25px;
            color: white;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .social-link:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: translateY(-2px);
        }

        @media (max-width: 768px) {
            .hero h1 {
                font-size: 2.5em;
            }
            
            .hero p {
                font-size: 1.1em;
            }
            
            .cta-buttons {
                flex-direction: column;
                align-items: center;
            }
            
            .section {
                padding: 25px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Hero Section -->
        <div class="hero">
            <h1>John Developer</h1>
            <p>Full-Stack Developer | Problem Solver | Code Enthusiast</p>
            <div class="cta-buttons">
                <a href="#projects" class="btn btn-primary">View My Work</a>
                <a href="#coffee" class="btn btn-secondary">☕ Buy Me a Coffee</a>
            </div>
        </div>

        <!-- Skills Section -->
        <div class="section">
            <h2>My Skills</h2>
            <div class="skills-grid">
                <div class="skill-card">
                    <h3>Frontend</h3>
                    <p>React, Vue.js, HTML5, CSS3, JavaScript, TypeScript</p>
                </div>
                <div class="skill-card">
                    <h3>Backend</h3>
                    <p>Node.js, Python, Java, PostgreSQL, MongoDB</p>
                </div>
                <div class="skill-card">
                    <h3>Tools & Others</h3>
                    <p>Git, Docker, AWS, Figma, Agile Development</p>
                </div>
            </div>
        </div>

        <!-- Projects Section -->
        <div class="section" id="projects">
            <h2>Featured Projects</h2>
            <div class="projects-grid">
                <div class="project-card">
                    <div class="project-image">🚀 E-Commerce Platform</div>
                    <div class="project-content">
                        <h3>Modern E-Commerce Site</h3>
                        <p>Full-stack e-commerce solution with React frontend, Node.js backend, and integrated payment processing.</p>
                    </div>
                </div>
                <div class="project-card">
                    <div class="project-image">📱 Task Manager App</div>
                    <div class="project-content">
                        <h3>Productivity Dashboard</h3>
                        <p>React-based task management application with real-time updates and team collaboration features.</p>
                    </div>
                </div>
                <div class="project-card">
                    <div class="project-image">🤖 AI Chat Bot</div>
                    <div class="project-content">
                        <h3>Customer Service Bot</h3>
                        <p>Intelligent chatbot using Python and NLP libraries to provide automated customer support.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Buy Me a Coffee Section -->
        <div class="section coffee-section" id="coffee">
            <h2>Support My Work</h2>
            <div class="coffee-card">
                <h3>☕ Buy Me a Coffee</h3>
                <p>Enjoying my projects? Support my continued development and learning!</p>
                <div class="price-tag">$5</div>
                <button class="btn btn-primary" onclick="showSupportMessage()">Support Now</button>
                <p style="margin-top: 20px; font-size: 0.9em; opacity: 0.8;">
                    Your support helps me dedicate more time to open-source projects and learning new technologies!
                </p>
            </div>
        </div>

        <!-- About Section -->
        <div class="section">
            <h2>About Me</h2>
            <p style="font-size: 1.2em; line-height: 1.8; text-align: center; max-width: 800px; margin: 0 auto;">
                I'm a passionate developer who loves creating solutions that make a difference. With experience in both frontend and backend technologies, I enjoy building full-stack applications that solve real-world problems. I'm always learning and excited to take on new challenges!
            </p>
        </div>

        <!-- Contact Section -->
        <div class="section contact">
            <h2>Let's Connect</h2>
            <p style="font-size: 1.2em; margin-bottom: 30px;">Ready to work together or just want to say hi?</p>
            <div class="social-links">
                <a href="mailto:your.email@example.com" class="social-link">📧 Email</a>
                <a href="#" class="social-link">💼 LinkedIn</a>
                <a href="#" class="social-link">🐱 GitHub</a>
                <a href="#" class="social-link">🐦 Twitter</a>
            </div>
        </div>
    </div>

    <script>
        function showSupportMessage() {
            alert('Thank you for your support! 🙏\n\nIn a real implementation, this would redirect to your preferred payment platform (PayPal, Stripe, etc.)');
        }

        // Smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Add some interactive animations
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, observerOptions);

        // Observe all sections for animation
        document.querySelectorAll('.section').forEach(section => {
            section.style.opacity = '0';
            section.style.transform = 'translateY(30px)';
            section.style.transition = 'all 0.6s ease';
            observer.observe(section);
        });
    </script>
</body>
</html>