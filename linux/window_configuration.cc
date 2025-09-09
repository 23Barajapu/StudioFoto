#include "window_configuration.h"

#include <flutter_linux/flutter_linux.h>

const unsigned int kFlutterWindowTitlebarHeight = 0;

GtkWindow* CreateFlutterWindow(int width, int height) {
  gtk_init(nullptr, nullptr);
  GtkWindow* window = GTK_WINDOW(gtk_window_new());
  gtk_window_set_title(window, "Prime Foto Studio");
  gtk_window_set_default_size(window, width, height);
  return window;
}
