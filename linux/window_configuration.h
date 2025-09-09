#ifndef WINDOW_CONFIGURATION_H_
#define WINDOW_CONFIGURATION_H_

#include <gtk/gtk.h>

// The height of the title bar.
const unsigned int kFlutterWindowTitlebarHeight = 0;

// Creates a Flutter window with the given dimensions.
GtkWindow* CreateFlutterWindow(int width, int height);

#endif
