<?php
if (php_sapi_name() !== 'cli' && realpath(__FILE__) === realpath($_SERVER['SCRIPT_FILENAME'])) {
    http_response_code(403);
    exit('Forbidden');
}

$currentPath = $_SERVER['REQUEST_URI'] ?? '/';
$base = rtrim(BASE_URL, '/');
$navItems = [
    'Home' => $base . '/index.php',
    'About Us' => $base . '/about.php',
    'Galeria' => $base . '/gallery.php',
    'Rezervo' => $base . '/reservation.php',
    'Kontakt' => $base . '/contact.php'
];
?>
<header class="site-header" id="siteHeader">
    <div class="container">
        <nav class="site-nav" aria-label="Main navigation">
            <div class="brand">
                <a href="<?php echo $base; ?>"><?php echo htmlspecialchars(APP_NAME); ?></a>
            </div>
            <button class="nav-toggle" id="navToggle" aria-label="Toggle navigation" aria-expanded="false">
                <span></span>
                <span></span>
                <span></span>
            </button>
            <ul class="nav-menu" id="navMenu" role="menubar">
                <?php foreach ($navItems as $label => $url):
                    $active = basename(parse_url($currentPath, PHP_URL_PATH)) === basename($url) ? ' active' : '';
                ?>
                    <li class="nav-item<?php echo $active; ?>" role="none">
                        <a href="<?php echo $url; ?>" role="menuitem"><?php echo htmlspecialchars($label); ?></a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </nav>
    </div>
</header>
<script>
    (function() {
        var toggle = document.getElementById('navToggle');
        var menu = document.getElementById('navMenu');
        var backdrop = document.getElementById('navBackdrop');
        var header = document.getElementById('siteHeader');
        
        if (!toggle || !menu) return;
        
        function openMenu() {
            toggle.classList.add('active');
            menu.classList.add('active');
            backdrop.classList.add('active');
            toggle.setAttribute('aria-expanded', 'true');
            document.body.style.overflow = 'hidden';
        }
        
        function closeMenu() {
            toggle.classList.remove('active');
            menu.classList.remove('active');
            backdrop.classList.remove('active');
            toggle.setAttribute('aria-expanded', 'false');
            document.body.style.overflow = '';
        }
        
        toggle.addEventListener('click', function() {
            if (menu.classList.contains('active')) {
                closeMenu();
            } else {
                openMenu();
            }
        });
        
        backdrop.addEventListener('click', closeMenu);
        
        menu.querySelectorAll('a').forEach(function(link) {
            link.addEventListener('click', closeMenu);
        });
        
        window.addEventListener('scroll', function() {
            if (window.scrollY > 10) {
                header.classList.add('scrolled');
            } else {
                header.classList.remove('scrolled');
            }
        });
        
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && menu.classList.contains('active')) {
                closeMenu();
                toggle.focus();
            }
        });
    })();
</script>