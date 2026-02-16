    </main><!-- /.main-content -->
</div><!-- /.app-layout -->

<!-- Botón flotante para cambiar de rol -->
<a href="/proyectoo_22_/mvc_programa/index.php" class="floating-logout-btn" title="Cambiar Rol">
    <i data-lucide="log-out"></i>
    <span>Cambiar Rol</span>
</a>

<script>
    // Initialize Lucide icons
    lucide.createIcons();

    // Sidebar toggle (mobile)
    (function() {
        var toggle = document.getElementById('sidebarToggle');
        var sidebar = document.getElementById('sidebar');
        var overlay = document.getElementById('sidebarOverlay');

        if (toggle && sidebar && overlay) {
            toggle.addEventListener('click', function() {
                sidebar.classList.toggle('open');
                overlay.classList.toggle('active');
            });

            overlay.addEventListener('click', function() {
                sidebar.classList.remove('open');
                overlay.classList.remove('active');
            });
        }
    })();
</script>

</body>
</html>
