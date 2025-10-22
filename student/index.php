<?php
$page_title = "Home";
include '../includes/header.php';
include '../config/database.php';

$hero_title = "SPARK";
$hero_subtitle = "Sanjivani Platform for AI, Research & Knowledge";
$hero_description = "Empowering students through innovation, technology, and collaborative learning";
$about_title = "About SPARK";
$about_content = "SPARK is the premier student club at Sanjivani University dedicated to fostering innovation in Artificial Intelligence, Research, and Knowledge sharing.";
$about_content2 = "We bring together passionate students from various departments to collaborate, learn, and create cutting-edge solutions that address real-world challenges.";
$vision_content = "To create a thriving ecosystem of innovators and researchers who leverage technology to solve global challenges and contribute to society's advancement through AI and emerging technologies.";
$mission_content = "To provide students with hands-on experience in cutting-edge technologies, foster collaborative research, organize impactful events, and build a community of lifelong learners and innovators.";

$query = "SELECT * FROM home_content WHERE is_active = 1 ORDER BY section_order";
$result = mysqli_query($conn, $query);

if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        switch ($row['section_key']) {
            case 'hero_title':
                $hero_title = $row['section_content'];
                break;
            case 'hero_subtitle':
                $hero_subtitle = $row['section_title'];
                $hero_description = $row['section_content'];
                break;
            case 'about_title':
                $about_title = $row['section_title'];
                $about_content = $row['section_content'];
                break;
            case 'vision':
                $vision_content = $row['section_content'];
                break;
            case 'mission':
                $mission_content = $row['section_content'];
                break;
        }
    }
}

$tech_query = "SELECT * FROM technologies WHERE is_active = 1 ORDER BY display_order";
$technologies = mysqli_query($conn, $tech_query);

$features_query = "SELECT * FROM features WHERE is_active = 1 ORDER BY display_order";
$features = mysqli_query($conn, $features_query);
?>

<div class="hero-section">
    <div class="container">
        <h1 class="display-3 fw-bold mb-3"><i class="fas fa-fire-alt"></i> <?php echo htmlspecialchars($hero_title); ?></h1>
        <h2 class="mb-4"><?php echo htmlspecialchars($hero_subtitle); ?></h2>
        <p class="lead"><?php echo htmlspecialchars($hero_description); ?></p>
        <a href="events.php" class="btn btn-light btn-lg mt-3">Explore Events</a>
    </div>
</div>

<main class="container my-5">
    <section class="mb-5">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h2 class="fw-bold mb-4"><?php echo htmlspecialchars($about_title); ?></h2>
                <p class="lead"><?php echo htmlspecialchars($about_content); ?></p>
                <p><?php echo htmlspecialchars($about_content2); ?></p>
            </div>
            <div class="col-md-6">
                <img src="https://images.unsplash.com/photo-1522071820081-009f0129c71c?w=600&h=400&fit=crop" alt="SPARK Team" class="img-fluid rounded shadow">
            </div>
        </div>
    </section>

    <section class="mb-5">
        <h2 class="text-center fw-bold mb-5">Our Vision & Mission</h2>
        <div class="row g-4">
            <div class="col-md-6">
                <div class="card feature-card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <div class="text-center mb-3">
                            <i class="fas fa-eye fa-3x text-primary"></i>
                        </div>
                        <h4 class="card-title text-center mb-3">Our Vision</h4>
                        <p class="card-text"><?php echo htmlspecialchars($vision_content); ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card feature-card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <div class="text-center mb-3">
                            <i class="fas fa-bullseye fa-3x text-success"></i>
                        </div>
                        <h4 class="card-title text-center mb-3">Our Mission</h4>
                        <p class="card-text"><?php echo htmlspecialchars($mission_content); ?></p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="mb-5">
        <h2 class="text-center fw-bold mb-5">Technologies We Explore</h2>
        <div class="row g-4">
            <?php
            if ($technologies && mysqli_num_rows($technologies) > 0) {
                while ($tech = mysqli_fetch_assoc($technologies)) {
                    $color_classes = ['text-primary', 'text-success', 'text-info', 'text-warning', 'text-danger'];
                    $color = $color_classes[array_rand($color_classes)];
            ?>
                <div class="col-md-3 col-sm-6">
                    <div class="card feature-card border-0 shadow-sm text-center p-4">
                        <i class="fas <?php echo htmlspecialchars($tech['icon']); ?> fa-3x <?php echo $color; ?> mb-3"></i>
                        <h5><?php echo htmlspecialchars($tech['title']); ?></h5>
                        <p class="small"><?php echo htmlspecialchars($tech['description']); ?></p>
                    </div>
                </div>
            <?php
                }
            } else {
            ?>
                <div class="col-12 text-center">
                    <p class="text-muted">No technologies available at the moment.</p>
                </div>
            <?php } ?>
        </div>
    </section>

    <section class="mb-5">
        <h2 class="text-center fw-bold mb-5">Why Join SPARK?</h2>
        <div class="row g-4">
            <?php
            if ($features && mysqli_num_rows($features) > 0) {
                while ($feature = mysqli_fetch_assoc($features)) {
                    $color_classes = ['text-primary', 'text-success', 'text-warning', 'text-info', 'text-danger'];
                    $color = $color_classes[array_rand($color_classes)];
            ?>
                <div class="col-md-4">
                    <div class="card feature-card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="mb-3">
                                <i class="fas <?php echo htmlspecialchars($feature['icon']); ?> fa-2x <?php echo $color; ?>"></i>
                            </div>
                            <h5 class="card-title"><?php echo htmlspecialchars($feature['title']); ?></h5>
                            <p class="card-text"><?php echo htmlspecialchars($feature['description']); ?></p>
                        </div>
                    </div>
                </div>
            <?php
                }
            } else {
            ?>
                <div class="col-12 text-center">
                    <p class="text-muted">No features available at the moment.</p>
                </div>
            <?php } ?>
        </div>
    </section>

    <section class="text-center bg-light p-5 rounded">
        <h2 class="fw-bold mb-3">Ready to Ignite Your Potential?</h2>
        <p class="lead mb-4">Join SPARK today and be part of something extraordinary!</p>
        <a href="events.php" class="btn btn-primary btn-lg me-2">View Events</a>
        <a href="contact.php" class="btn btn-outline-primary btn-lg">Contact Us</a>
    </section>
</main>

<?php include '../includes/footer.php'; ?>
