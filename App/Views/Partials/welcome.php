<h1>This is the <?= $data['tajuk'] ?> Page from <?= $data['tajuk'] ?> Controller</h1>

<?php if (isset($_SESSION['username'])): ?>
        <h1>Welcome <?= ucfirst($_SESSION['username']) ?></h1>
<?php endif; ?>

<p>Welcome to the <?= $data['tajuk'] ?> page.</p>

