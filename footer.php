<footer class="site-footer">
    <div class="footer-inner">
        <div class="footer-brand">
            <a href="<?php echo esc_url(home_url('/')); ?>">
                <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/img/logo-white.svg'); ?>" alt="<?php bloginfo('name'); ?>" width="160" height="42" class="footer-logo">
            </a>
            <p class="footer-tagline">Helping foreign buyers find their dream properties in Ciudad Quesada.</p>
            <div class="footer-social">
                <a href="#" aria-label="Facebook">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/></svg>
                </a>
                <a href="#" aria-label="Instagram">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"/><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/></svg>
                </a>
            </div>
        </div>

        <div class="footer-links">
            <div class="footer-col">
                <h4>Quick links</h4>
                <ul>
                    <li><a href="<?php echo esc_url(home_url('/')); ?>">Home</a></li>
                    <li><a href="<?php echo esc_url(home_url('/about')); ?>">About us</a></li>
                    <li><a href="<?php echo esc_url(home_url('/how-it-works')); ?>">How it works</a></li>
                    <li><a href="<?php echo esc_url(home_url('/services')); ?>">Services</a></li>
                    <li><a href="<?php echo esc_url(home_url('/properties')); ?>">All properties</a></li>
                    <li><a href="<?php echo esc_url(home_url('/dictionary')); ?>">Property dictionary</a></li>
                    <li><a href="<?php echo esc_url(home_url('/contact')); ?>">Contact us</a></li>
                </ul>
            </div>
            <div class="footer-col">
                <h4>Property types</h4>
                <ul>
                    <li><a href="<?php echo esc_url(home_url('/properties/?property_type=villa')); ?>">Villas</a></li>
                    <li><a href="<?php echo esc_url(home_url('/properties/?property_type=apartment')); ?>">Apartments</a></li>
                    <li><a href="<?php echo esc_url(home_url('/properties/?property_type=townhouse')); ?>">Townhouses</a></li>
                    <li><a href="<?php echo esc_url(home_url('/properties/?features=pool')); ?>">Properties with pool</a></li>
                </ul>
            </div>
            <div class="footer-col">
                <h4>Legal</h4>
                <ul>
                    <li><a href="<?php echo esc_url(home_url('/privacy-policy')); ?>">Privacy policy</a></li>
                    <li><a href="<?php echo esc_url(home_url('/terms-of-use')); ?>">Terms of use</a></li>
                    <li><a href="<?php echo esc_url(home_url('/cookie-policy')); ?>">Cookie policy</a></li>
                </ul>
            </div>
            <div class="footer-col">
                <h4>Contact us</h4>
                <ul>
                    <li><a href="mailto:post@spaniabolig.no">post@spaniabolig.no</a></li>
                    <li><a href="tel:+34681914891">+34 681 914 891</a></li>
                    <li><span>Ciudad Quesada, Spain</span></li>
                </ul>
            </div>
        </div>
    </div>
    <div class="footer-bottom">
        <p>© <?php echo date('Y'); ?> Copyright Spaniabolig SL</p>
        <div class="footer-bottom-links">
            <a href="<?php echo esc_url(home_url('/sitemap.xml')); ?>">Sitemap</a>
            <a href="<?php echo esc_url(home_url('/robots.txt')); ?>">Robots.txt</a>
        </div>
    </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
