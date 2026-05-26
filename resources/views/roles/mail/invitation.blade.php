<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Careers | Kawach Technology Pvt Ltd</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="https://www.kawachtech.com/">
                <i class="fas fa-shield-alt brand-icon me-2"></i>
                <span class="fw-bold tracking-wide">KAWACH</span>
                <span class="text-accent ms-1 fs-6">TECHNOLOGY</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item"><a class="nav-link" href="#">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Services</a></li>
                    <li class="nav-item"><a class="nav-link active" href="#">Careers</a></li>
                    <li class="nav-item ms-lg-3">
                        <a class="btn btn-accent px-4 py-2" href="#openings">Explore Roles</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <header class="hero-section text-white d-flex align-items-center">
        <div class="container text-center text-lg-start">
            <div class="row align-items-center">
                <div class="col-lg-7">
                    <span class="badge bg-soft-accent text-accent mb-3 px-3 py-2 text-uppercase fw-semibold tracking-wider">We're Hiring</span>
                    <h1 class="display-4 fw-bold mb-3">Building Intelligent Solutions for a Digital Future</h1>
                    <h2 class="h3 fw-light text-muted-blue mb-4">Your Vision, Our Code. Your Career, Our Mission.</h2>
                    <p class="lead mb-5 opacity-90">Join a next-generation software development team crafting secure, scalable, and powerful digital infrastructure for enterprises worldwide.</p>
                    <div class="d-flex flex-column flex-sm-row gap-3 justify-content-center justify-content-lg-start">
                        <a href="#openings" class="btn btn-accent btn-lg px-4 fw-semibold">View Open Positions</a>
                        <a href="#culture" class="btn btn-outline-light btn-lg px-4">Life At Kawach</a>
                    </div>
                </div>
                <div class="col-lg-5 d-none d-lg-block">
                    <div class="hero-graphic-element position-relative">
                        <div class="floating-card c-1"><i class="fas fa-lock text-accent me-2"></i> Secure</div>
                        <div class="floating-card c-2"><i class="fas fa-chart-line text-success me-2"></i> Scalable</div>
                        <div class="floating-card c-3"><i class="fas fa-brain text-warning me-2"></i> Smart</div>
                        <div class="shield-circle-bg">
                            <i class="fas fa-shield-alt fa-5x text-accent opacity-25"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <section id="culture" class="py-6 bg-dark-blue text-white">
        <div class="container text-center">
            <h2 class="fw-bold mb-2">Why Build Your Future With Kawach?</h2>
            <p class="text-muted-blue max-w-600 mx-auto mb-5">We offer more than just a job; we provide a collaborative landscape where innovation meets robust engineering standards.</p>
            
            <div class="row g-4 text-start">
                <div class="col-md-4">
                    <div class="kawach-card p-4">
                        <div class="icon-box mb-3"><i class="fas fa-shield-halved"></i></div>
                        <h4 class="h5 fw-bold card-title">Security-First Mindset</h4>
                        <p class="small text-muted-blue">Work on complex digital systems deployed with top-tier security layers, data compliance measures, and cloud configurations.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="kawach-card p-4">
                        <div class="icon-box mb-3"><i class="fas fa-globe"></i></div>
                        <h4 class="h5 fw-bold card-title">Global Impact</h4>
                        <p class="small text-muted-blue">Deploy high-performance APIs and tailored enterprise systems powering global markets across the US, UK, Australia, and beyond.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="kawach-card p-4">
                        <div class="icon-box mb-3"><i class="fas fa-laptop-code"></i></div>
                        <h4 class="h5 fw-bold card-title">Cutting-Edge Stack</h4>
                        <p class="small text-muted-blue">Leverage architectures natively across AWS, Azure, Google Cloud, building high-speed microservices and intuitive cross-platform software.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="openings" class="py-6 bg-light-blue text-navy">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="fw-bold">Current Global Opportunities</h2>
                <p class="text-secondary max-w-600 mx-auto">Help businesses streamline operations and accelerate digital transformations.</p>
            </div>

            <div class="row g-4">
                <div class="col-lg-6">
                    <div class="job-card bg-white p-4 shadow-sm rounded-4 d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-3">
                        <div>
                            <span class="badge bg-soft-primary text-primary mb-2">Engineering</span>
                            <h4 class="h5 fw-bold mb-1 text-navy">Senior Backend Developer (API Architecture)</h4>
                            <p class="small text-muted mb-0"><i class="fas fa-map-marker-alt me-2"></i>Remote / Global</p>
                        </div>
                        <button class="btn btn-outline-dark-blue btn-sm px-4 py-2 flex-shrink-0" onclick="openApplyModal('Senior Backend Developer')">Apply Now</button>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="job-card bg-white p-4 shadow-sm rounded-4 d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-3">
                        <div>
                            <span class="badge bg-soft-primary text-primary mb-2">Cloud Infrastructure</span>
                            <h4 class="h5 fw-bold mb-1 text-navy">Cloud Security Architect (AWS / Azure)</h4>
                            <p class="small text-muted mb-0"><i class="fas fa-map-marker-alt me-2"></i>Hybrid / Uttar Pradesh Office</p>
                        </div>
                        <button class="btn btn-outline-dark-blue btn-sm px-4 py-2 flex-shrink-0" onclick="openApplyModal('Cloud Security Architect')">Apply Now</button>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="job-card bg-white p-4 shadow-sm rounded-4 d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-3">
                        <div>
                            <span class="badge bg-soft-primary text-primary mb-2">Engineering</span>
                            <h4 class="h5 fw-bold mb-1 text-navy">Full Stack Software Developer</h4>
                            <p class="small text-muted mb-0"><i class="fas fa-map-marker-alt me-2"></i>Full-time / Remote</p>
                        </div>
                        <button class="btn btn-outline-dark-blue btn-sm px-4 py-2 flex-shrink-0" onclick="openApplyModal('Full Stack Software Developer')">Apply Now</button>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="job-card bg-white p-4 shadow-sm rounded-4 d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-3">
                        <div>
                            <span class="badge bg-soft-primary text-primary mb-2">Product Development</span>
                            <h4 class="h5 fw-bold mb-1 text-navy">UI/UX Product Designer</h4>
                            <p class="small text-muted mb-0"><i class="fas fa-map-marker-alt me-2"></i>Full-time</p>
                        </div>
                        <button class="btn btn-outline-dark-blue btn-sm px-4 py-2 flex-shrink-0" onclick="openApplyModal('UI/UX Product Designer')">Apply Now</button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="modal fade" id="applyModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content text-navy">
                <div class="modal-header border-0 bg-navy text-white">
                    <h5 class="modal-title fw-bold" id="modalTitle">Join Our Team</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-shadow="none" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <form id="applyForm" onsubmit="submitApplication(event)">
                        <input type="hidden" id="appliedPosition">
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-muted">Full Name</label>
                            <input type="text" class="form-control" required placeholder="John Doe">
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-muted">Email Address</label>
                            <input type="email" class="form-control" required placeholder="name@domain.com">
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-muted">GitHub / Portfolio URL</label>
                            <input type="url" class="form-control" placeholder="https://github.com/username">
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-muted">Upload Resume (PDF)</label>
                            <input type="file" class="form-control" required accept=".pdf">
                        </div>
                        <button type="submit" class="btn btn-dark-blue w-100 mt-2 py-2">Submit Application</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <footer class="bg-navy text-white py-5 border-top border-dark">
        <div class="container text-center">
            <div class="d-flex align-items-center justify-content-center mb-3">
                <i class="fas fa-shield-alt text-accent me-2 fs-4"></i>
                <span class="fw-bold fs-4 tracking-wide">KAWACH TECHNOLOGY</span>
            </div>
            <p class="text-muted-blue small mb-4">Empowering Businesses with Custom Software Solutions and Trusted IT Security Services.</p>
            <div class="mt-4 pt-4 border-top border-secondary opacity-50 small">
                <p>&copy; 2026 Kawach Technology Private Limited. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const bootstrapModal = new bootstrap.Modal(document.getElementById('applyModal'));
        
        function openApplyModal(jobName) {
            document.getElementById('modalTitle').innerText = 'Apply for ' + jobName;
            document.getElementById('appliedPosition').value = jobName;
            bootstrapModal.show();
        }

        function submitApplication(e) {
            e.preventDefault();
            const position = document.getElementById('appliedPosition').value;
            alert(`Thank you for applying to Kawach Technology for the ${position} position! Our talent team will review your application.`);
            bootstrapModal.hide();
            document.getElementById('applyForm').reset();
        }
    </script>
</body>
</html>