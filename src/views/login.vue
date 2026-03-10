<template>
  <div class="page">

    <!-- Fondo con efecto -->
    <div class="bg-overlay"></div>

    <!-- Contenido centrado -->
    <div class="container">

      <!-- Card del login -->
      <div class="card">

        <!-- Logo / Título -->
        <div class="card-header">
          <div class="logo">
            <span class="logo-text">MUEBLERIA <span class="logo-accent">MERELES</span></span>
            <span class="logo-icon">▶</span>
          </div>
        </div>

        <!-- Formulario -->
        <div class="card-body">
          <h2 class="title">Acceder</h2>

          <!-- Error -->
          <div v-if="error" class="alert-error">
            <span>⚠ {{ error }}</span>
          </div>

          <div class="field">
            <label class="field-label">Correo electrónico:</label>
            <input
              v-model="email"
              type="email"
              class="field-input"
              placeholder="correo@ejemplo.com"
              required
            />
          </div>

          <div class="field">
            <label class="field-label">Contraseña:</label>
            <div class="input-wrapper">
              <input
                v-model="password"
                :type="showPassword ? 'text' : 'password'"
                class="field-input"
                placeholder="Contraseña"
                required
              />
              <button type="button" class="toggle-pass" @click="showPassword = !showPassword">
                {{ showPassword ? '👁' : '🙈' }}
              </button>
            </div>
          </div>

          <div class="field-row">
            <label class="remember">
              <input type="checkbox" v-model="remember" />
              <span>Recordarme</span>
            </label>
          </div>

          <button
            class="btn-login"
            :class="{ loading: isLoading }"
            :disabled="isLoading"
            @click="login"
          >
            <span v-if="!isLoading">ACCEDER</span>
            <span v-else class="spinner">●&nbsp;●&nbsp;●</span>
          </button>

        </div>
      </div>

      <!-- Footer -->
      <p class="footer-text">© 2026 MUEBLERIA MERELES — Todos los derechos reservados</p>
    </div>
  </div>
</template>

<script>
import axios from "axios";

export default {
  data() {
    return {
      email: "",
      password: "",
      error: "",
      isLoading: false,
      showPassword: false,
      remember: false,
    };
  },
  methods: {
    async login() {
      this.error = "";
      this.isLoading = true;
      try {
        const response = await axios.post(
          "http://127.0.0.1:8000/api/login",
          {
            email: this.email,
            password: this.password,
          },
          {
            headers: { Accept: "application/json" },
          }
        );

        const { token, role } = response.data;

        // Guardar token y rol
        localStorage.setItem("token", token);
        localStorage.setItem("role", role);

        // Redirigir según rol
        if (role === "admin") {
          alert("Bienvenido Admin 👑");
        } else {
          alert("Bienvenido Operador 🙌");
        }

      } catch (err) {
        this.error = "Correo o contraseña incorrectos.";
      } finally {
        this.isLoading = false;
      }
    },
  },
};
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Inter:wght@300;400;500;600&display=swap');

* {
  box-sizing: border-box;
  margin: 0;
  padding: 0;
}

.page {
  min-height: 100vh;
  background-color: #1a1a1a;
  display: flex;
  align-items: center;
  justify-content: center;
  font-family: 'Inter', sans-serif;
  position: relative;
  overflow: hidden;
}

.bg-overlay {
  position: absolute;
  inset: 0;
  background:
    radial-gradient(ellipse at 20% 50%, rgba(220, 38, 38, 0.08) 0%, transparent 60%),
    radial-gradient(ellipse at 80% 20%, rgba(220, 38, 38, 0.05) 0%, transparent 50%);
  pointer-events: none;
}

.container {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 20px;
  width: 100%;
  padding: 20px;
  position: relative;
  z-index: 1;
}

.card {
  background: #242424;
  border: 1px solid #333;
  border-radius: 8px;
  width: 100%;
  max-width: 480px;
  overflow: hidden;
  box-shadow: 0 25px 60px rgba(0, 0, 0, 0.5);
}

.card-header {
  background: #1a1a1a;
  padding: 28px;
  text-align: center;
  border-bottom: 1px solid #2e2e2e;
}

.logo {
  display: inline-flex;
  align-items: center;
  gap: 6px;
}

.logo-text {
  font-family: 'Bebas Neue', sans-serif;
  font-size: 28px;
  letter-spacing: 3px;
  color: #ffffff;
}

.logo-accent {
  color: #dc2626;
}

.logo-icon {
  color: #dc2626;
  font-size: 20px;
}

.card-body {
  padding: 32px;
}

.title {
  font-size: 20px;
  font-weight: 400;
  color: #e5e5e5;
  margin-bottom: 24px;
  letter-spacing: 0.5px;
}

.alert-error {
  background: rgba(220, 38, 38, 0.1);
  border: 1px solid rgba(220, 38, 38, 0.3);
  border-radius: 4px;
  padding: 10px 14px;
  color: #f87171;
  font-size: 14px;
  margin-bottom: 20px;
}

.field {
  margin-bottom: 20px;
}

.field-label {
  display: block;
  font-size: 13px;
  color: #a0a0a0;
  margin-bottom: 8px;
  font-weight: 500;
}

.field-input {
  width: 100%;
  background: transparent;
  border: none;
  border-bottom: 1px solid #444;
  padding: 10px 0;
  color: #e5e5e5;
  font-size: 15px;
  font-family: 'Inter', sans-serif;
  outline: none;
  transition: border-color 0.2s;
}

.field-input:focus {
  border-bottom-color: #dc2626;
}

.field-input::placeholder {
  color: #555;
}

.input-wrapper {
  position: relative;
}

.input-wrapper .field-input {
  padding-right: 36px;
}

.toggle-pass {
  position: absolute;
  right: 0;
  top: 50%;
  transform: translateY(-50%);
  background: none;
  border: none;
  cursor: pointer;
  font-size: 16px;
  color: #666;
  padding: 4px;
}

.toggle-pass:hover {
  color: #999;
}

.field-row {
  display: flex;
  align-items: center;
  justify-content: space-between;
  margin-bottom: 28px;
}

.remember {
  display: flex;
  align-items: center;
  gap: 8px;
  color: #888;
  font-size: 13px;
  cursor: pointer;
}

.remember input[type="checkbox"] {
  accent-color: #dc2626;
  width: 14px;
  height: 14px;
}

.btn-login {
  width: 100%;
  background: #dc2626;
  color: #fff;
  border: none;
  border-radius: 4px;
  padding: 14px;
  font-size: 14px;
  font-weight: 700;
  letter-spacing: 2px;
  cursor: pointer;
  transition: background 0.2s, transform 0.1s;
  font-family: 'Bebas Neue', sans-serif;
  font-size: 18px;
}

.btn-login:hover:not(:disabled) {
  background: #b91c1c;
}

.btn-login:active:not(:disabled) {
  transform: scale(0.98);
}

.btn-login:disabled {
  opacity: 0.7;
  cursor: not-allowed;
}

.btn-login.loading {
  background: #7f1d1d;
}

.spinner {
  animation: pulse 1s infinite;
}

@keyframes pulse {
  0%, 100% { opacity: 1; }
  50% { opacity: 0.3; }
}

.footer-text {
  color: #555;
  font-size: 12px;
  text-align: center;
}
</style>