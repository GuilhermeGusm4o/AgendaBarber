'use client';

import { useEffect, useState } from 'react';

const AppointmentsPage = () => {
  const [appointments, setAppointments] = useState([]);
  const [date, setDate] = useState('');

  // Função para buscar os compromissos
  const fetchAppointments = async () => {
    const res = await fetch('/api/appointments');

    // Verifica se a resposta é OK (status 200)
    if (!res.ok) {
      console.error('Erro ao buscar compromissos:', res.statusText);
      return; // Retorna se houver um erro
    }

    try {
      const data = await res.json();
      setAppointments(data);
    } catch (error) {
      console.error('Erro ao converter resposta para JSON:', error);
    }
  };

  // Função para adicionar um novo compromisso
  const addAppointment = async (e) => {
    e.preventDefault();
    if (!date) return;

    await fetch('/api/appointments', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({ date, customerName: 'Cliente Teste' }),
    });

    setDate('');
    fetchAppointments(); // Recarrega os compromissos depois de adicionar
  };

  useEffect(() => {
    fetchAppointments();
  }, []);

  return (
    <div>
      <h1>Compromissos</h1>
      <form onSubmit={addAppointment}>
        <input
          type="date"
          value={date}
          onChange={(e) => setDate(e.target.value)}
          required
        />
        <button type="submit">Adicionar Compromisso</button>
      </form>
      <h2>Lista de Compromissos</h2>
      <ul>
        {appointments.map((appointment) => (
          <li key={appointment.id}>
            {new Date(appointment.date).toLocaleDateString()} -{' '}
            {appointment.customerName}
          </li>
        ))}
      </ul>
    </div>
  );
};

export default AppointmentsPage;
