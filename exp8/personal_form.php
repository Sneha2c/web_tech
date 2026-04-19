<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exp 8 - Contact Me (PHP Form)</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 40px 20px;
        }
        
        .container {
            max-width: 600px;
            margin: 0 auto;
        }
        
        .header {
            text-align: center;
            color: white;
            margin-bottom: 30px;
        }
        
        .header h1 {
            font-size: 36px;
            margin-bottom: 10px;
        }
        
        .form-card {
            background: white;
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.2);
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #333;
        }
        
        .form-group input,
        .form-group textarea,
        .form-group select {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            font-size: 16px;
        }
        
        .error-input {
            border-color: #ff4757 !important;
        }
        
        .error-message {
            color: #ff4757;
            font-size: 12px;
            margin-top: 5px;
            display: block;
        }
        
        .btn {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
        }
        
        .btn:hover {
            transform: translateY(-2px);
            cursor: pointer;
        }
        
        .required {
            color: #ff4757;
        }
        
        .nav-links {
            text-align: center;
            margin-top: 20px;
        }
        
        .nav-links a {
            color: white;
            text-decoration: none;
            margin: 0 10px;
            padding: 8px 16px;
            background: rgba(255,255,255,0.2);
            border-radius: 20px;
            display: inline-block;
        }
        
        .success-message {
            background: #00b894;
            color: white;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
            text-align: center;
        }
        
        .data-display {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            margin-top: 20px;
        }
        
        .data-display p {
            margin: 8px 0;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="header">
        <h1>📧 Contact Me</h1>
        <p>Experiment 8 - PHP Form Handling & Validation</p>
    </div>
    
    <div class="form-card">
        <?php
        // Initialize variables
        $errors = [];
        $success = false;
        $fullname = $email = $phone = $subject = $message = "";
        
        // Handle form submission
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
            
            $fullname = trim($_POST['fullname'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $phone = trim($_POST['phone'] ?? '');
            $subject = trim($_POST['subject'] ?? '');
            $message = trim($_POST['message'] ?? '');
            
            // Validation
            if (empty($fullname)) {
                $errors['fullname'] = "Full name is required";
            } elseif (strlen($fullname) < 3) {
                $errors['fullname'] = "Name must be at least 3 characters";
            } elseif (!preg_match("/^[a-zA-Z\s]+$/", $fullname)) {
                $errors['fullname'] = "Name can only contain letters";
            }
            
            if (empty($email)) {
                $errors['email'] = "Email is required";
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors['email'] = "Valid email is required";
            }
            
            if (!empty($phone) && !preg_match("/^[0-9]{10}$/", $phone)) {
                $errors['phone'] = "Phone must be 10 digits";
            }
            
            if (empty($subject)) {
                $errors['subject'] = "Subject is required";
            }
            
            if (empty($message)) {
                $errors['message'] = "Message is required";
            } elseif (strlen($message) < 10) {
                $errors['message'] = "Message must be at least 10 characters";
            }
            
            if (empty($errors)) {
                $success = true;
                $_SESSION['contact_data'] = [
                    'fullname' => $fullname,
                    'email' => $email,
                    'phone' => $phone,
                    'subject' => $subject,
                    'message' => $message,
                    'date' => date('Y-m-d H:i:s')
                ];
            }
        }
        
        // Show success message
        if ($success):
        ?>
            <div class="success-message">
                ✓ Message Sent Successfully!
            </div>
            <h2 style="text-align: center;">Thank You, <?php echo htmlspecialchars($fullname); ?>!</h2>
            <div class="data-display">
                <h3>Your Submitted Information:</h3>
                <p><strong>Name:</strong> <?php echo htmlspecialchars($fullname); ?></p>
                <p><strong>Email:</strong> <?php echo htmlspecialchars($email); ?></p>
                <?php if (!empty($phone)): ?>
                    <p><strong>Phone:</strong> <?php echo htmlspecialchars($phone); ?></p>
                <?php endif; ?>
                <p><strong>Subject:</strong> <?php echo htmlspecialchars($subject); ?></p>
                <p><strong>Message:</strong> <?php echo nl2br(htmlspecialchars($message)); ?></p>
                <p><small>Submitted on: <?php echo date('Y-m-d H:i:s'); ?></small></p>
            </div>
            <a href="personal_form.php" class="btn" style="text-align: center; display: block; text-decoration: none; margin-top: 20px;">Send Another Message</a>
            
        <?php else: ?>
        
        <form method="POST" action="">
            <div class="form-group">
                <label>Full Name <span class="required">*</span></label>
                <input type="text" name="fullname" value="<?php echo htmlspecialchars($fullname); ?>" class="<?php echo isset($errors['fullname']) ? 'error-input' : ''; ?>">
                <?php if (isset($errors['fullname'])): ?>
                    <span class="error-message"><?php echo $errors['fullname']; ?></span>
                <?php endif; ?>
            </div>
            
            <div class="form-group">
                <label>Email <span class="required">*</span></label>
                <input type="email" name="email" value="<?php echo htmlspecialchars($email); ?>" class="<?php echo isset($errors['email']) ? 'error-input' : ''; ?>">
                <?php if (isset($errors['email'])): ?>
                    <span class="error-message"><?php echo $errors['email']; ?></span>
                <?php endif; ?>
            </div>
            
            <div class="form-group">
                <label>Phone</label>
                <input type="tel" name="phone" value="<?php echo htmlspecialchars($phone); ?>" class="<?php echo isset($errors['phone']) ? 'error-input' : ''; ?>">
                <?php if (isset($errors['phone'])): ?>
                    <span class="error-message"><?php echo $errors['phone']; ?></span>
                <?php endif; ?>
            </div>
            
            <div class="form-group">
                <label>Subject <span class="required">*</span></label>
                <select name="subject" class="<?php echo isset($errors['subject']) ? 'error-input' : ''; ?>">
                    <option value="">Select a subject</option>
                    <option value="General Inquiry" <?php echo ($subject == 'General Inquiry') ? 'selected' : ''; ?>>General Inquiry</option>
                    <option value="Project Collaboration" <?php echo ($subject == 'Project Collaboration') ? 'selected' : ''; ?>>Project Collaboration</option>
                    <option value="Feedback" <?php echo ($subject == 'Feedback') ? 'selected' : ''; ?>>Feedback</option>
                </select>
                <?php if (isset($errors['subject'])): ?>
                    <span class="error-message"><?php echo $errors['subject']; ?></span>
                <?php endif; ?>
            </div>
            
            <div class="form-group">
                <label>Message <span class="required">*</span></label>
                <textarea name="message" rows="5" class="<?php echo isset($errors['message']) ? 'error-input' : ''; ?>"><?php echo htmlspecialchars($message); ?></textarea>
                <?php if (isset($errors['message'])): ?>
                    <span class="error-message"><?php echo $errors['message']; ?></span>
                <?php endif; ?>
            </div>
            
            <button type="submit" name="submit" class="btn">Send Message ✉️</button>
        </form>
        
        <?php endif; ?>
    </div>
    
    <div class="nav-links">
        <a href="ecommerce_form.php">🛍️ E-commerce Form</a>
    </div>
</div>

</body>
</html>