import { NextResponse } from 'next/server';

const API_URL = 'http://localhost:8080/api/appointments';

export async function GET() {
  const res = await fetch(API_URL);
  const appointments = await res.json();
  return NextResponse.json(appointments);
}

export async function POST(request) {
  const data = await request.json();
  const response = await fetch(API_URL, {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
    },
    body: JSON.stringify(data),
  });
  return NextResponse.json(await response.json());
}
