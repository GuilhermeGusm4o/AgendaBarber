'use client';

import { useEffect, useState } from 'react';
import './style.css';

const AppointmentsPage = () => {
  const [appointments, setAppointments] = useState([]);
  const [date, setDate] = useState('');
  const [customerName, setCustomerName] = useState('');
  const [editingAppointment, setEditingAppointment] = useState(null);

  // busca dos compromissos
  const fetchAppointments = async () => {
    const res = await fetch('/api/appointments');

    if (!res.ok) {
      console.error('Erro ao buscar compromissos:', res.statusText);
      return;
    }

    try {
      const data = await res.json();
      setAppointments(data);
    } catch (error) {
      console.error('Erro ao converter resposta para JSON:', error);
    }
  };

  // Adicionar ou atualizar um compromisso
  const handleAppointmentSubmit = async (e) => {
    e.preventDefault();
    if (!date || !customerName) return;

    try {
      const method = editingAppointment ? 'PUT' : 'POST';
      const response = await fetch(
        '/api/appointments' +
          (editingAppointment ? `/${editingAppointment.id}` : ''),
        {
          method,
          headers: {
            'Content-Type': 'application/json',
          },
          body: JSON.stringify({ date, customerName }),
        }
      );

      if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`);
      }

      setDate('');
      setCustomerName('');
      fetchAppointments();
    } catch (error) {
      console.error('Error creating/updating appointment:', error);
    }
  };

  const cancelEdit = () => {
    setDate('');
    setCustomerName('');
    setEditingAppointment(null);
  };

  const deleteAppointment = async (id) => {
    if (confirm('Tem certeza que deseja excluir este compromisso?')) {
      try {
        const response = await fetch(`/api/appointments`, {
          method: 'DELETE',
          headers: {
            'Content-Type': 'application/json',
          },
          body: JSON.stringify({ id }),
        });

        if (!response.ok) {
          throw new Error(`HTTP error! status: ${response.status}`);
        }

        fetchAppointments(); // Atualiza a lista de compromissos
      } catch (error) {
        console.error('Error deleting appointment:', error);
      }
    }
  };

  const editAppointment = (appointment) => {
    const formattedDate = new Date(appointment.date)
      .toISOString()
      .split('T')[0];
    setDate(formattedDate);
    setCustomerName(appointment.customerName);
    setEditingAppointment(appointment);
  };

  useEffect(() => {
    fetchAppointments();
  }, []);

  return (
    <div className="max-w-lg mx-auto p-6 bg-white rounded-lg shadow-md">
      <h1 className="text-2xl font-bold text-center mb-4">Compromissos</h1>
      <form
        onSubmit={handleAppointmentSubmit}
        className="flex flex-col space-y-4"
      >
        <input
          type="text"
          placeholder="Nome Completo"
          value={customerName}
          onChange={(e) => setCustomerName(e.target.value)}
          required
          className="border border-gray-300 rounded-lg p-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
        />
        <input
          type="date"
          value={date}
          onChange={(e) => setDate(e.target.value)}
          required
          className="border border-gray-300 rounded-lg p-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
        />
        <button
          type="submit"
          className="bg-blue-500 text-white font-semibold rounded-lg py-2 hover:bg-blue-600 transition duration-200"
        >
          {editingAppointment
            ? 'Atualizar Compromisso'
            : 'Adicionar Compromisso'}
        </button>
        {editingAppointment && ( // Mostra o botão de cancelar se estiver editando
          <button
            type="button"
            onClick={cancelEdit}
            className="bg-gray-300 text-black font-semibold rounded-lg py-2 hover:bg-gray-400 transition duration-200"
          >
            Cancelar Edição
          </button>
        )}
      </form>
      <h2 className="text-xl font-semibold mt-6">Lista de Compromissos</h2>
      <ul className="mt-4 space-y-2">
        {appointments.map((appointment) => (
          <li
            key={appointment.id}
            className="border border-gray-200 rounded-lg p-4 bg-gray-50 flex justify-between items-center"
          >
            <div>
              {new Date(appointment.date).toLocaleDateString()} -{' '}
              {appointment.customerName}
            </div>
            <div className="flex space-x-2">
              <button
                onClick={() => editAppointment(appointment)}
                className="text-blue-600 hover:underline"
              >
                Editar
              </button>
              <button
                onClick={() => deleteAppointment(appointment.id)}
                className="text-red-600 hover:underline"
              >
                Excluir
              </button>
            </div>
          </li>
        ))}
      </ul>
    </div>
  );
};

export default AppointmentsPage;
