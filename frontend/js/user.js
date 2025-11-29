document.addEventListener('DOMContentLoaded', () => {
    // --- LÓGICA PARA MOSTRAR EL NOMBRE DE USUARIO ---
    const usernameElement = document.querySelector('.username');
    const storedName = localStorage.getItem('userName');

    if (usernameElement) {
        if (storedName) {
            // Reemplaza el texto (####) por el nombre real
            usernameElement.textContent = storedName;
        } else {
            // Fallback por si no hay nombre guardado
            usernameElement.textContent = "Usuario Anónimo";
        }
    }

    // --- LÓGICA DE LOGOUT (Ya existente) ---
    const logoutButton1 = document.querySelector('.logout');
    if (logoutButton1) {
        logoutButton1.addEventListener('click', async () => {
            const token = localStorage.getItem('authToken');
            
            if (token) {
                try {
                    await fetch('http://127.0.0.1:8001/view/logout', {
                        method: 'GET',
                        headers: {
                            'Authorization': token,
                            'Content-Type': 'application/json'
                        }
                    });
                } catch (error) {
                    console.error("Error al intentar logout:", error);
                }
            }
            
            localStorage.clear();
            window.location.href = '../index.html'; 
        });
    }
    // 1. Referencia al botón de Logout
    const logoutButton = document.querySelector('.logout');

    if (logoutButton) {
        logoutButton.addEventListener('click', async () => {
            
            // 2. Recuperar el token actual para enviarlo en la cabecera
            const token = localStorage.getItem('authToken');

            // Si hay token, intentamos cerrar sesión en el servidor
            if (token) {
                try {
                    // Consumo del Endpoint Logout definido en usermicroservice.http
                    const response = await fetch('http://127.0.0.1:8001/view/logout', {
                        method: 'GET',
                        headers: {
                            'Authorization': token, // Enviamos el token actual
                            'Content-Type': 'application/json'
                        }
                    });

                    if (response.ok) {
                        console.log("Sesión cerrada exitosamente en el servidor.");
                    } else {
                        console.warn("El servidor reportó un error, pero se procederá con el logout local.");
                    }

                } catch (error) {
                    console.error("Error de conexión al intentar logout:", error);
                }
            }

            // 3. Limpieza total de datos locales (Token, Roles, etc.)
            // Esto cumple con tu requerimiento de "eliminar el resto de información"
            localStorage.clear();

            // 4. Redirección al index (Login)
            // Ajusta la ruta "../index.html" según la estructura de tus carpetas. 
            // Si dashboard está en /views/user/, el index está dos niveles arriba o en la raíz.
            window.location.href = '../index.html'; 
        });
    }
});