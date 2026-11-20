<?php
declare(strict_types=1);

/**
 * ITCS333 A4 starter — contact form.
 * Requirements (see README rubric):
 *   - GET: render the form
 *   - POST: validate name (required, 2-60 chars), email (valid), message (required, 10-1000 chars)
 *   - invalid POST: re-render with inline errors + repopulated fields (200)
 *   - valid POST: redirect (302/303) to a success page (PRG)
 *   - escape all output with htmlspecialchars
 */

require_once __DIR__ . '/../src/validate.php';

// TODO: implement the form handling here.
$name = '';
$email = '';
$message = '';
$errors = [];

function escape_html(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = is_string($_POST['name'] ?? null) ? $_POST['name'] : '';
    $email = is_string($_POST['email'] ?? null) ? $_POST['email'] : '';
    $message = is_string($_POST['message'] ?? null) ? $_POST['message'] : '';

    if (!validate_required($name)) {
        $errors['name'] = 'Name is required.';
    } elseif (!validate_length($name, 2, 60)) {
        $errors['name'] = 'Name must be 2 to 60 characters.';
    }

    if (!validate_email($email)) {
        $errors['email'] = 'Enter a valid email address.';
    }

    if (!validate_required($message)) {
        $errors['message'] = 'Message is required.';
    } elseif (!validate_length($message, 10, 1000)) {
        $errors['message'] = 'Message must be 10 to 1000 characters.';
    }

    if (empty($errors)) {
        header('Location: index.php?success=1', true, 303);
        exit;
    }
}

$success = ($_SERVER['REQUEST_METHOD'] === 'GET'
    && ($_GET['success'] ?? '') === '1');
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Contact Us</title>
</head>
<body>
  <h1>Contact Us</h1>
 <!-- TODO: render the form, inline errors, and repopulated values -->
    <?php if ($success): ?>
    <p>Thank you! Your message has been sent successfully.</p>
<?php else: ?>
    
  <form method="post" action="index.php">
    <label>Name
    <input type="text" name="name" value="<?= escape_html($name) ?>">
</label>
<?php if (isset($errors['name'])): ?>
    <span><?= escape_html($errors['name']) ?></span>
<?php endif; ?>
<br>
    <label>Email
    <input type="email" name="email" value="<?= escape_html($email) ?>">
</label>
<?php if (isset($errors['email'])): ?>
    <span><?= escape_html($errors['email']) ?></span>
<?php endif; ?>
<br>
    <label>Message
    <textarea name="message"><?= escape_html($message) ?></textarea>
</label>
<?php if (isset($errors['message'])): ?>
    <span><?= escape_html($errors['message']) ?></span>
<?php endif; ?>
<br>
    <button type="submit">Send</button>
  </form>
    <?php endif; ?>
</body>
</html>
