import { NextRequest, NextResponse } from 'next/server'

export async function POST(request: NextRequest) {
  try {
    const body = await request.json()
    const { nimi, sahkoposti, puhelinnumero, viesti } = body

    if (!nimi || !sahkoposti || !viesti) {
      return NextResponse.json(
        { error: 'Pakollisia kenttiä puuttuu' },
        { status: 400 }
      )
    }

    console.log('Contact form submission:', { nimi, sahkoposti, puhelinnumero, viesti })

    return NextResponse.json(
      { message: 'Viesti lähetetty onnistuneesti!' },
      { status: 200 }
    )
  } catch {
    return NextResponse.json(
      { error: 'Palvelinvirhe. Yritä uudelleen.' },
      { status: 500 }
    )
  }
}
