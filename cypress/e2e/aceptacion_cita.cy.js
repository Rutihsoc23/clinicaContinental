describe('Pruebas de la Clínica Continental', () => {
  it('Debería abrir la página de inicio', () => {
    // Esto abre la página principal de tu Laravel
    cy.visit('/'); 
    
    // Si quieres abrir una página específica, como doctores:
    // cy.visit('/doctores'); 
  });
});

describe('Flujo Completo - Gestión de Citas Clínica Continental', () => {

    const pacienteNombre = 'Santi Quispe';
    const doctorNombre = 'Ana Luz Ramos';

    it('Debería crear paciente, doctor y agendar cita exitosamente', () => {
        
        // --- PASO 1: CREAR PACIENTE ---
        cy.visit('/pacientes/create');
        cy.get('input[name="nombre_paciente"]').type(pacienteNombre);
        cy.get('input[name="apellido_paterno_paciente"]').type('Mamani');
        cy.get('input[name="dni_paciente"]').type('22233344');
        cy.get('input[name="email_paciente"]').type('santi@mail.com');
        cy.get('button[type="submit"]').click();
        cy.contains(pacienteNombre).should('be.visible');

        // --- PASO 2: CREAR DOCTOR ---
        cy.visit('/doctores/create');
        cy.get('input[name="nombre_doctor"]').type(doctorNombre);
        cy.get('input[name="dni_doctor"]').type('11122233');
        // Seleccionamos la primera especialidad disponible
        cy.get('select[name="especialidad_id"]').select(1); 
        cy.get('button[type="submit"]').click();
        cy.contains(doctorNombre).should('be.visible');

        // --- PASO 3: AGENDAR LA CITA ---
        cy.visit('/citas/create');

        // Seleccionamos los datos que acabamos de crear
        // Usamos .contains() dentro del select para mayor precisión
        cy.get('select[name="paciente_id"]').select(pacienteNombre);
        cy.get('select[name="doctor_id"]').select(doctorNombre);

        // Llenamos fecha y hora
        cy.get('input[name="fecha_cita"]').type('2026-03-01');
        cy.get('input[name="hora_cita"]').type('09:00');

        // Seleccionamos el primer estado disponible (ej. Confirmada)
        cy.get('select[name="estado_id"]').select(1);

        // Enviar formulario
        cy.get('button[type="submit"]').click();

        // --- VALIDACIÓN FINAL ---
        // Verificamos redirección al listado (index)
        cy.url().should('include', '/citas');
        // Verificamos que la cita aparezca en la tabla de reportes
        cy.contains(pacienteNombre).should('be.visible');
        cy.contains(doctorNombre).should('be.visible');
    });

});