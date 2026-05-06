    </div> <!-- End Main Content -->
    <script>
        // Simple active state handling
        const currentPath = window.location.pathname.split('/').pop();
        document.querySelectorAll('.nav-item a').forEach(link => {
            if (link.getAttribute('href') === currentPath) {
                document.querySelectorAll('.nav-item').forEach(i => i.classList.remove('active'));
                link.parentElement.classList.add('active');
            }
        });
    </script>
</body>
</html>
