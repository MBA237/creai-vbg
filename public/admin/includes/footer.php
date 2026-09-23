        </div><!-- /.admin-content -->

    </main>

</div><!-- /.admin-layout -->

<script src="/js/admin-sidebar.js" defer></script>
<script src="/js/admin-confirm.js" defer></script>
<?php foreach (($extraJs ?? []) as $extraSrc): ?>
<script src="<?= htmlspecialchars($extraSrc) ?>" defer></script>
<?php endforeach; ?>
</body>
</html>