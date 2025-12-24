<main class="container mx-auto">
  <h1>This is Blog</h1>
  <?php for ($i = 0; $i < 20; $i++): ?>
    <li>Blog Element - <?= $i + 1 ?></li>
  <?php endfor; ?>
</main>