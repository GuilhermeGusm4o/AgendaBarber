import { NextResponse } from 'next/server';

const API_URL = process.env.NEXT_PUBLIC_API_URL;

export async function GET() {
  console.log('API_URL:', API_URL);
  try {
    const res = await fetch(API_URL);
    console.log('Response:', res);

    if (!res.ok) {
      throw new Error('Erro ao buscar compromissos');
    }

    const appointments = await res.json();
    console.log(appointments);
    return NextResponse.json(appointments);
  } catch (error) {
    console.error('Erro ao fazer o GET:', error);
    return NextResponse.json({ error: error.message }, { status: 500 });
  }
}

export async function POST(request) {
  try {
    const data = await request.json();
    // console.log(data);
    const response = await fetch(API_URL, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify(data),
    });
    if (!response.ok) {
      throw new Error(`Error: ${response.statusText}`);
    }

    const responseData = await response.json();
    return NextResponse.json(responseData);
  } catch (error) {
    console.error('Error creating appointment:', error);
    return NextResponse.json({ error: error.message }, { status: 500 });
  }
}
