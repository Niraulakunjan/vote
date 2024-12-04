import smtplib
import sys
from email.mime.text import MIMEText
from email.mime.multipart import MIMEMultipart

def send_email(voter_email, voter_id, plain_password):
    # SMTP server configuration (example: Gmail)
    smtp_server = "smtp.gmail.com"
    smtp_port = 587
    sender_email = "niraula67kunjan@gmail.com"  # Replace with your email
    sender_password = "uglm yoih aiqa cupg"  # Use an app-specific password if needed

    # Email content
    subject = "Your Voter Registration Details"
    body = f"""
    Dear Voter,

    Your voter registration was successful. Here are your details:
    - Voter ID: {voter_id}
    - Password: {plain_password}

    Please keep this information secure.

    Regards,
    Election Team
    """

    # Create email structure
    msg = MIMEMultipart()
    msg['From'] = sender_email
    msg['To'] = voter_email
    msg['Subject'] = subject
    msg.attach(MIMEText(body, 'plain'))

    try:
        # Connect to SMTP server and send the email
        with smtplib.SMTP(smtp_server, smtp_port) as server:
            server.starttls()
            server.login(sender_email, sender_password)
            server.sendmail(sender_email, voter_email, msg.as_string())
        print("Email sent successfully.")
    except Exception as e:
        print(f"Failed to send email: {e}")

if __name__ == "__main__":
    if len(sys.argv) == 4:
        email = sys.argv[1]
        voter_id = sys.argv[2]
        password = sys.argv[3]
        send_email(email, voter_id, password)
    else:
        print("Usage: python send_email.py <email> <voter_id> <password>")
