<footer class="bg-dark">
    <div class="container">
        <?php 
            foreach (getFlashMsg('success') as $flash)
            {
                echo '<p class="pt-10 flash flash-'.$flash['type'].'">' . $flash['content'] . '</p>';
            };
        ?>

        <div class="py-10 txt-center txt-white">
            © <?php echo date('Y') ?> - Zeephyr
        </div>
    </div>
</footer>