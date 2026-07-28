<?php
if (php_sapi_name() !== 'cli' && realpath(__FILE__) === realpath($_SERVER['SCRIPT_FILENAME'])) {
    http_response_code(403);
    exit('Forbidden');
}
?>
            </div>
        </main>
    </div>
    <script>
        (function() {
            var toggle = document.getElementById('adminMenuToggle');
            var sidebar = document.getElementById('adminSidebar');
            var backdrop = document.getElementById('adminMobileBackdrop');
            if (!toggle || !sidebar) return;

            function openSidebar() {
                toggle.classList.add('active');
                sidebar.classList.add('open');
                backdrop.classList.add('active');
                toggle.setAttribute('aria-expanded', 'true');
                document.body.style.overflow = 'hidden';
            }

            function closeSidebar() {
                toggle.classList.remove('active');
                sidebar.classList.remove('open');
                backdrop.classList.remove('active');
                toggle.setAttribute('aria-expanded', 'false');
                document.body.style.overflow = '';
            }

            toggle.addEventListener('click', function() {
                if (sidebar.classList.contains('open')) {
                    closeSidebar();
                } else {
                    openSidebar();
                }
            });

            backdrop.addEventListener('click', closeSidebar);

            sidebar.querySelectorAll('a').forEach(function(link) {
                link.addEventListener('click', closeSidebar);
            });

            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && sidebar.classList.contains('open')) {
                    closeSidebar();
                    toggle.focus();
                }
            });
        })();
    </script>
</body>
</html>
