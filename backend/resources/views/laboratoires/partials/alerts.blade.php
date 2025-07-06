<div style="position: fixed; top: 3rem; right: 1rem; z-index: 1050; width: 300px;">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert" style="transition: opacity 1s ease;">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert" style="transition: opacity 1s ease;">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const alertList = document.querySelectorAll('.alert-dismissible');
        alertList.forEach(function (alert) {
            setTimeout(() => {
                alert.style.opacity = '0';
                setTimeout(() => {
                    const bsAlert = bootstrap.Alert.getOrCreateInstance(alert);
                    bsAlert.close();
                }, 1000); // Wait for opacity transition before closing
            }, 5000); // Auto dismiss after 5 seconds
        });
    });
</script>
