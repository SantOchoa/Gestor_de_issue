document.addEventListener('DOMContentLoaded', () => {
    const loginForm = document.querySelector('form[name="loginForm"]');
    const emailInput = document.getElementById('email');
    const passwordInput = document.getElementById('password');
    const errorContainer = document.querySelector('.info.input');

    
    if (errorContainer) {
        errorContainer.style.display = 'none'; 
    }

    loginForm.addEventListener('submit', async (e) => {
        e.preventDefault(); 
        if (errorContainer) errorContainer.style.display = 'none';

        const email = emailInput.value;
        const password = passwordInput.value;
        if (!email || !password) {
            alert("Por favor ingrese usuario y contraseña");
            return;
        }

        try {
            const response = await fetch('http://127.0.0.1:8001/login', {
                method: 'POST', // 
                headers: {
                    'Content-Type': 'application/json' // 
                },
                body: JSON.stringify({
                    email: email,
                    pwd: password 
                })
            });

            if (response.ok) {
                const data = await response.json();
                localStorage.setItem('authToken', data.token);
                    // Guardar rol si es necesario para redirección
                 localStorage.setItem('userRole', data.user.role);
                if (data.user.role === 'admin') {
                    window.location.href = 'admin/dashboart-admin.html';
                    return;
                }else if (data.user.role === 'gestor') {
                    window.location.href = 'user/dashboart-user.html';
                    return;
                }
            } else {
                console.error("Error en login");
                if (errorContainer) {
                    errorContainer.style.display = 'flex'; 
                }
            }

        } catch (error) {
            console.error("Error de red o servidor:", error);
            if (errorContainer) {
                errorContainer.style.display = 'flex';
                const errorText = errorContainer.querySelector('h3');
                if(errorText) errorText.innerText = "Error de conexión con el servidor.";
            }
        }
    });
});