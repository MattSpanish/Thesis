import time

from watchdog.events import FileSystemEventHandler
from watchdog.observers import Observer

from generate_plot import generate_plot


class CSVFileModifiedHandler(FileSystemEventHandler):
    def __init__(self, csv_file):
        super().__init__()
        self.csv_file = csv_file

    def on_modified(self, event):
        if event.src_path == self.csv_file:
            print("Detected change in CSV file. Regenerating plot...")
            generate_plot(self.csv_file)
            print("Plot regenerated successfully.")

def monitor_changes(csv_file):
    event_handler = CSVFileModifiedHandler(csv_file)
    observer = Observer()
    observer.schedule(event_handler, '.', recursive=False)
    observer.start()

    try:
        while True:
            time.sleep(1)
    except KeyboardInterrupt:
        observer.stop()
    observer.join()

if __name__ == "__main__":
    monitor_changes('Book3.csv')  # Replace 'Book3.csv' with your CSV file path
